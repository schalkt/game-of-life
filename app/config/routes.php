<?php

namespace App;

// index
$app->get("/", function () use ($app) {

    $app->render('index.phtml', [], 200);

});

// index
$app->get("/cli/step(/:step(/:seed(/:density)))", function (

    $step = 0,
    $seed = 1,
    $density = 5

) use ($app) {

    $options = array_replace($app->config('game'), [
        'step' => $step,
        'seed' => $seed,
        'density' => $density,
    ]);

    $game = new \Gol\Game($options);
    $game->render();

});

// api
$app->group('/api', function () use ($app) {

    $app->get('/step(/:step(/:seed(/:density)))', function (

        $step = 0,
        $seed = 1,
        $density = 5

    ) use ($app) {

        $options = array_replace($app->config('game'), [
            'step' => $step,
            'seed' => $seed,
            'density' => $density,
        ]);

        $game = new \Gol\Game($options);
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $content = json_encode($game->exportJSON());
        $response->write($content);
        $response->getBody();

    });

    $app->post('/step(/:step)', function (

        $step = 0

    ) use ($app) {

        $options = array_replace($app->config('game'), [
            'step' => $step,
            'seed' => rand(1, 2017),
            'density' => rand(4, 7),
            'import' => $app->request->getBody(),
        ]);

        $game = new \Gol\Game($options);
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $content = json_encode($game->exportJSON());
        $response->write($content);
        $response->getBody();

    });

    $app->post('/step(/:step)', function (

        $step = 0,
        $seed = 1,
        $density = 5

    ) use ($app) {

        $options = array_replace($app->config('game'), [
            'step' => $step,
            'seed' => $seed,
            'density' => $density,
        ]);

        $game = new \Gol\Game($options);
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $content = json_encode($game->exportJSON());
        $response->write($content);
        $response->getBody();

    });


});
