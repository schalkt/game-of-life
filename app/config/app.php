<?php

return array(
    'mode' => 'production',
    'debug' => true,
    'templates.path' => PATH_APP . '/app/views',
    'view' => '\Slim\LayoutView',
    'layout' => 'layout.phtml',
    'game' => require 'game.php'
);