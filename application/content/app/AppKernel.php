<?php

use Che\BlogModule\Service\ContentProvider\CachedContentProvider;
use Che\BlogModule\Service\ContentProvider\JsonRpcContentProvider;
use Che\ContentModule\Dao\PageDao;
use Che\ContentModule\Service\JsonRpcPageService;
use JsonRPC\Client;
use Phalcon\Config as PhConfig;
use Phalcon\Di as PhDI;
use Phalcon\Di\FactoryDefault as PhFactoryDefault;
use Phalcon\Logger\Adapter\File as PhFileLogger;
use Phalcon\Logger\Formatter\Line as PhLoggerFormatter;
use Phalcon\Mvc\Application as PhApplication;
use Phalcon\Mvc\Micro as PhMicro;
use Phalcon\Mvc\Micro\Collection as PhMicroCollection;
use Phalcon\Registry as PhRegistry;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Memcache as BackMemCached;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

use Che\BlogModule\Exception;

/**
 * AbstractBootstrap
 *
 * @property PhDI $diContainer
 */
class AppKernel
{
    /**
     * @var PhMicro
     */
    protected $application = null;

    /**
     * @var PhDI
     */
    protected $diContainer = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Runs the application
     *
     * @return PhApplication
     */
    public function run()
    {
        $this->initDi();
//        $this->initLoader();
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
//        PhDI::setDefault($this->diContainer);
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

//    /**
//     * Initializes the autoloader
//     */
//    protected function initLoader()
//    {
//        /**
//         * Use the composer autoloader
//         */
//        require_once APP_PATH . '/vendor/autoload.php';
//    }

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
        $registry->contributors  = [];
        $registry->executionTime = 0;
        $registry->language      = 'en';
        $registry->memory        = 0;
        $registry->noindex       = false;
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

        $this->application->setEventsManager($eventsManager);
    }

    /**
     * Initializes the routes
     */
    protected function initServices()
    {
        $this->diContainer->setShared(
            'db',
            function () {
                return new PdoMysql(
                    [
                        'host'     => '172.28.1.1',
                        'username' => 'root',
                        'password' => 'root',
                        'dbname'   => 'phalcon',
                    ]
                );
            }
        );

        $this->diContainer->setShared(
            'che_content.dao.page',
            [
                "className" => PageDao::class,
                "arguments" => [
                    [
                        "type" => "service",
                        "name" => "modelsManager",
                    ],
                ]
            ]
        );

        $this->diContainer->setShared(
            'che_content.service.json_rpc_page_service',
            [
                "className" => JsonRpcPageService::class,
                "arguments" => [
                    [
                        "type" => "service",
                        "name" => "che_content.dao.page",
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
