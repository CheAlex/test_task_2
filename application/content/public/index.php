<?php

use Composer\Autoload\ClassLoader;

try {
    if (true !== defined('APP_PATH')) {
        define('APP_PATH', dirname(dirname(__FILE__)));
        // kernel.root_dir
    }

    /** @var ClassLoader $loader */
    $loader = require __DIR__.'/../app/autoload.php';

    (new AppKernel())->run();
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL . $e->getTraceAsString();
}
