<?php

use Blog\Service\CachedContentProvider;
use Blog\Service\ContentProviderInterface;
use Blog\Service\ContentResponse;
use Blog\Service\RpcContentProvider;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Store\Dao\PageDao;
use Store\Service\PageService;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = new Loader();

$loader->registerNamespaces(
    [
        "Blog\\Service" => __DIR__ . "/services/",
    ]
);

$loader->register();

$di = new FactoryDefault();

$di->setShared(
    'rpc_content_provider',
    [
        "className" => RpcContentProvider::class,
    ]
);

$di->setShared(
    'cached_content_provider',
    [
        "className" => CachedContentProvider::class,
        "arguments" => [
            [
                "type" => "service",
                "name" => "rpc_content_provider",
            ],
        ]
    ]
);

$di->setShared(
    'content_provider',
    function() {
        return $this->get("cached_content_provider");
    }
);

$app = new Micro($di);

$app->get(
    '/{id:.+}',
    function () use ($app) {
        $t = 0;

        // remove first slash
        $uri = substr($app->request->getURI(), 1);
        $host = $app->request->getServerName();

//        $mc = new Memcached();
//        die;

        /** @var ContentProviderInterface $contentProvider */
        $contentProvider = $app->getDI()->get('content_provider');
        $content = $contentProvider->get($host, $uri);

        if (ContentResponse::CODE_OK !== $content->getCode()) {
            return new Response($content->getError(), $content->getCode());
        }

        return new Response($content->getHtml(), $content->getCode());
    }
);

$app->get(
    '/test1',
    function () use ($app) {
        $t = 1;

        echo 'test1';
    }
);

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, 'Not Found')->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

$app->handle();
