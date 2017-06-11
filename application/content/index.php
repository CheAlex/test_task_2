<?php

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
        "Store\\Toys" => __DIR__ . "/models/",
        "Store\\Service" => __DIR__ . "/services/",
        "Store\\Dao" => __DIR__ . "/dao/",
    ]
);

$loader->register();

$di = new FactoryDefault();

$di->setShared(
    'db',
    function () {
        return new PdoMysql(
            [
                'host'     => 'database_host',
                'username' => 'root',
                'password' => 'root',
                'dbname'   => 'phalcon',
            ]
        );
    }
);

$di->setShared(
    'page_dao',
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

$di->setShared(
    'page_service',
    [
        "className" => PageService::class,
        "arguments" => [
            [
                "type" => "service",
                "name" => "page_dao",
            ],
        ]
    ]
);

$app = new Micro($di);

$app->get(
    '/',
    function () use ($app) {
        $t = 0;

        $host = $app->request->getQuery('host');
        $uri  = $app->request->getQuery('uri');

        /** @var PageService $pageService */
        $pageService = $app->getDI()->get('page_service');
        $r = $pageService->get($host, $uri);

        $data = $pageService->get($host, $uri);

        $response = new Response();
        $response->setJsonContent(
            $data
        );

        return $response;
    }
);

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, 'Not Found')->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

$app->handle();
