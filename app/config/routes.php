<?php

namespace App;

// index
$app->get("/", function () {
    echo 'Hello :)' . PHP_EOL;
});

// index
$app->get("/cli/step(/:step(/:seed(/:density)))", function (

    $step = 0,
    $seed = 1,
    $density = 10

) {

    $game = new \Gol\Game([
        'step' => $step,
        'seed' => $seed,
        'density' => $density,
    ]);

    $game->render();

});

// api
$app->group('/api', function () use ($app) {

    $app->get('/step(/:step(/:seed(/:density)))', function (

        $step = 0,
        $seed = 1,
        $density = 10

    ) use ($app) {

        $game = new \Gol\Game([
            'step' => $step,
            'seed' => $seed,
            'density' => $density,
        ]);

        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $content = json_encode($game->exportJSON());
        $response->write($content);
        $response->getBody();

    });

});
