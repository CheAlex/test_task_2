<?php

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;

$loader = new Loader();

$loader->registerDirs([
    '../app/controllers/',
    '../app/models/',
]);

$loader->register();

$di = new FactoryDefault();

$di->set(
    'view',
    function () {
        $view = new View();

        $view->setViewsDir('../app/views/');

        return $view;
    }
);

$di->set(
    'url',
    function () {
        $url = new UrlProvider();

        $url->setBaseUri('/');

        return $url;
    }
);

$application = new Application($di);

try {
    $response = $application->handle();

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
