<?php

// index
$app->get("/", function () {
    echo 'Hello :)' . PHP_EOL;
});

// api
$app->group('/api', function () use ($app) {

    $app->get('/gen/next', function () use ($app) {

        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $content = json_encode([]);
        $response->write($content);
        die($response->getBody());

    });

});
