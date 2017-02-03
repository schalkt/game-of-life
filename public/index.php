<?php

namespace App;

define('PATH_APP', dirname(__DIR__));

require_once(PATH_APP . '/vendor/autoload.php');
$config = require(PATH_APP . '/app/config/app.php');
$app = new \Slim\Slim($config);

// not found page
$app->notFound(function () use ($app) {
    $app->render('404.html', [], 404);
});

// format errors
$app->error(function (\Exception $e) use ($app) {
    echo $e;
    $app->stop();
});

// load routes
require PATH_APP . '/app/config/routes.php';

$app->run();