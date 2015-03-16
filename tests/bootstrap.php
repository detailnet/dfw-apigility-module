<?php

$loader = null;
$basePath = realpath(__DIR__ . '/..') . '/';

if (file_exists($basePath . 'vendor/autoload.php')) {
    $loader = include $basePath . 'vendor/autoload.php';
} else {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$loader->add('DetailTest\Apigility', __DIR__);

if (!$config = @include $basePath . 'tests/configuration.php') {
    $config = require $basePath . 'tests/configuration.php.dist';
}
