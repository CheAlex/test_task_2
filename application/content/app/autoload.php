<?php

use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

return $loader;


//$composerDir = dirname(__DIR__) . '/vendor/composer/';
//
///**
// * We're a registering a set of 3rd party libraries from composer.
// */
//$loader = new \Phalcon\Loader();
//
//// register namespaces
//$loader->registerNamespaces(
//    require($composerDir . 'autoload_namespaces.php')
//);
//
//// register classes
//$loader->registerClasses(
//    require($composerDir . 'autoload_classmap.php')
//);
//
//$loader->register();
