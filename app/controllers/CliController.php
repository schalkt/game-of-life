<?php

namespace App;

class CliController
{

    public static function getInstance()
    {

        static $instance;

        if (!isset($instance)) {
            $instance = new self;
        }

        return $instance;

    }


    public function random($seed, $density)
    {

        $game = new \Gol\Game([
            'seed' => $seed,
            'density' => $density,
        ]);
        $game->step();
        $game->render();

    }

}