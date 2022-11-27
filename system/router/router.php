<?php

use \Core\Mvc\Router;

$router = new Router();

$router->get('/home', function($request, $response, array $args) {
    echo 'It\'s Home Page';
});

$router->get('/say/:hello:', function ($request, $response, array $args){
    echo 'Say: ' . $args['hello'];
});

return $router;