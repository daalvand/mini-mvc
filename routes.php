<?php


use Core\Contracts\App;
use Core\Contracts\Router;

/**
 * @var App    $app
 * @var Router $router
 */

$router = $app->get(Router::class);

$router->get('test', function () {
    return 'test view';
});
