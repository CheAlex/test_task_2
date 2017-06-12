<?php

$psr0 = __DIR__ . '/../vendor/composer/autoload_namespaces.php';
$psr4 = __DIR__ . '/../vendor/composer/autoload_psr4.php';

$map = array_merge(require($psr0), require($psr4));

$namespaces = array();
foreach ($map as $key => $value) {
    $key = trim($key, '\\');
    $dir = '/' . str_replace('\\', '/', $key) . '/';
    $namespaces[$key] = implode($dir . ';', $value) . $dir;
}

$dest = __DIR__ . '/../vendor/composer/autoload_namespaces.php';
$fp = fopen($dest, 'w');
fwrite($fp, "<?php\nreturn array(\n");
foreach ($namespaces as $key => $value) {
    fwrite($fp, " '$key' => '$value',\n");
}
fwrite($fp, ");\n");
fclose($fp);
