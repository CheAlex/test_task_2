<?php

use Che\BlogModule\Dao\CachedPageDao;
use Che\BlogModule\Dao\JsonRpcPageDao;
use Che\BlogModule\Exception;
use Che\BlogModule\Service\CacheService;
use JsonRPC\Client;
use Phalcon\Cache\Backend\Memcache as BackMemCached;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Config as PhConfig;
use Phalcon\Di as PhDI;
use Phalcon\Di\FactoryDefault as PhFactoryDefault;
use Phalcon\Logger\Adapter\File as PhFileLogger;
use Phalcon\Logger\Formatter\Line as PhLoggerFormatter;
use Phalcon\Mvc\Application as PhApplication;
use Phalcon\Mvc\Micro as PhMicro;
use Phalcon\Mvc\Micro\Collection as PhMicroCollection;
use Phalcon\Registry as PhRegistry;

/**
 * Class AppKernel
 */
class AppKernel
{
    /**
     * Application.
     *
     * @var PhMicro
     */
    protected $application;

    /**
     * Di container.
     *
     * @var PhDI
     */
    protected $diContainer;

    /**
     * Runs the application
     *
     * @return PhApplication
     */
    public function run()
    {
        $this->initDi();
        $this->initRegistry();
        $this->initApplication();
        $this->initConfig();
        $this->initLogger();
        $this->initErrorHandler();
        $this->initRoutes();
        $this->initServices();

        return $this->runApplication();
    }

    /**
     * Initializes the application
     */
    protected function initApplication()
    {
        $this->application = new PhMicro($this->diContainer);
    }

    /**
     * Initializes the Config container
     *
     * @throws Exception
     */
    protected function initConfig()
    {
        $fileName = APP_PATH . '/app/config/config.php';
        if (true !== file_exists($fileName)) {
            throw new Exception('Configuration file not found');
        }

        $configArray = require_once($fileName);
        $config = new PhConfig($configArray);

        $this->diContainer->setShared('config', $config);
    }

    /**
     * Initializes the Di container
     */
    protected function initDi()
    {
        $this->diContainer = new PhFactoryDefault();
    }

    /**
     * Initializes the error handlers
     */
    protected function initErrorHandler()
    {
        $registry = $this->diContainer->getShared('registry');
        $logger   = $this->diContainer->getShared('logger');

        ini_set(
            'display_errors',
            boolval('development' === $registry->mode)
        );
        error_reporting(E_ALL);

        set_error_handler(
            function ($errorNumber, $errorString, $errorFile, $errorLine) use ($logger) {
                if (0 === $errorNumber & 0 === error_reporting()) {
                    return;
                }

                $logger->error(
                    sprintf(
                        '[%s] [%s] %s - %s',
                        $errorNumber,
                        $errorLine,
                        $errorString,
                        $errorFile
                    )
                );
            }
        );

        set_exception_handler(
            function () use ($logger) {
                echo json_encode(debug_backtrace());
                $logger->error(json_encode(debug_backtrace()));
            }
        );

        register_shutdown_function(
            function () use ($logger, $registry) {
                $memory    = memory_get_usage() - $registry->memory;
                $execution = microtime(true) - $registry->executionTime;

                if ('development' === $registry->mode) {
                    $logger->info(
                        sprintf(
                            'Shutdown completed [%s] - [%s]',
                            $execution,
                            $memory
                        )
                    );
                }
            }
        );
    }

    /**
     * Initializes the loggers
     */
    protected function initLogger()
    {
        /** @var \Phalcon\Config $config */
        $config   = $this->diContainer->getShared('config');
        $fileName = $config->get('logger')
                           ->get('defaultFilename', 'application');
        $format   = $config->get('logger')
                           ->get('format', '[%date%][%type%] %message%');

        $logFile   = sprintf(
            '%s/var/logs/%s-%s.log',
            APP_PATH,
            date('Ymd'),
            $fileName
        );
        $formatter = new PhLoggerFormatter($format);
        $logger    = new PhFileLogger($logFile);
        $logger->setFormatter($formatter);

        $this->diContainer->setShared('logger', $logger);
    }

    /**
     * Initializes the registry
     */
    protected function initRegistry()
    {
        /**
         * Fill the registry with elements we will need
         */
        $registry = new PhRegistry();
        $registry->executionTime = 0;
        $registry->memory        = 0;
        $registry->mode          = 'development';

        $this->diContainer->setShared('registry', $registry);
    }

    /**
     * Initializes the routes
     */
    protected function initRoutes()
    {
        /** @var PhConfig $config */
        $config     = $this->diContainer->getShared('config');
        $routes     = $config->get('routes')->toArray();
        $middleware = $config->get('middleware')->toArray();

        foreach ($routes as $route) {
            $collection = new PhMicroCollection();
            $collection->setHandler($route['class'], true);
            if (true !== empty($route['prefix'])) {
                $collection->setPrefix($route['prefix']);
            }

            foreach ($route['methods'] as $verb => $methods) {
                foreach ($methods as $endpoint => $action) {
                    $collection->$verb($endpoint, $action);
                }
            }
            $this->application->mount($collection);
        }

        $eventsManager = $this->diContainer->getShared('eventsManager');

        foreach ($middleware as $element) {
            $class = $element['class'];
            $event = $element['event'];
            $eventsManager->attach('micro', new $class());
            $this->application->$event(new $class());
        }

        $this->application->setEventsManager($eventsManager);
    }

    /**
     * Initializes the services
     */
    protected function initServices()
    {
        $this->diContainer->setShared(
            'che_blog.content_provider.json_rpc',
            [
                'className' => JsonRpcPageDao::class,
                'arguments' => [
                    [
                        'type' => 'service',
                        'name' => 'che_blog.json_rpc.client',
                    ],
                ]
            ]
        );

        $this->diContainer->setShared(
            'che_blog.cache.content',
            function () {
                /** @var PhConfig $config */
                $config = $this->get('config');

                $frontCache = new FrontData($config->memcached->front->toArray());

                $cache = new BackMemCached(
                    $frontCache,
                    $config->memcached->back->toArray()
                );

                return $cache;
            }
        );

        $this->diContainer->setShared(
            'che_blog.content_provider.cached',
            [
                'className' => CachedPageDao::class,
                'arguments' => [
                    [
                        'type' => 'service',
                        'name' => 'che_blog.content_provider.json_rpc',
                    ],
                    [
                        'type' => 'service',
                        'name' => 'che_blog.cache.content',
                    ],
                ]
            ]
        );

        $this->diContainer->setShared(
            'che_blog.content_provider',
            function () {
                return $this->get('che_blog.content_provider.cached');
            }
        );

        $this->diContainer->setShared(
            'che_blog.json_rpc.client',
            function () {
                /** @var PhConfig $config */
                $config = $this->get('config');
                $serverUrl = $config['content']['json_rpc_server'];

                $client = new Client($serverUrl);

                return $client;
            }
        );

        $this->diContainer->setShared(
            'che_blog.service.cache_service',
            [
                'className' => CacheService::class,
                'arguments' => [
                    [
                        'type' => 'service',
                        'name' => 'che_blog.cache.content',
                    ],
                ]
            ]
        );
    }

    /**
     * Runs the main application
     *
     * @return PhApplication
     */
    protected function runApplication()
    {
        return $this->application->handle();
    }
}
