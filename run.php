#!/usr/bin/env php
<?php

namespace App;

define('PATH_APP', __DIR__);
require PATH_APP . '/vendor/autoload.php';

if (PHP_SAPI != 'cli') {
    die('CLI error' . PHP_EOL);
}

// for debug
function xd($data)
{
    print_r($data);
    die();
}

// get cli arguments and convert to url path
$argv = $GLOBALS['argv'];
array_shift($GLOBALS['argv']);
$pathInfo = '/' . implode('/', $argv);

// app config
$config = require(__DIR__ . '/app/config/app.php');
$app = new \Slim\Slim($config);

// environment setup
$app->environment = \Slim\Environment::mock([
    'PATH_INFO' => $pathInfo,
]);

// not found error handler
$app->notFound(function () use ($app) {
    $url = $app->environment['PATH_INFO'];
    echo "Error: Cannot route to $url" . PHP_EOL;
    $app->stop();
});

// format errors
$app->error(function (\Exception $e) use ($app) {
    echo $e;
    $app->stop();
});

// load routes
require __DIR__ . '/app/config/routes.php';

// run!
$app->run();
