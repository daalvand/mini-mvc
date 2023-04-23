<?php


use App\Http\AuthController;
use App\Http\HomeController;
use App\Http\Middlewares\AuthMiddleware;
use App\Http\Middlewares\CsrfMiddleware;
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

$router->get('', [HomeController::class, 'index']);
$router->get('cart-list', [HomeController::class, 'cart']);

$router->get('/profile', [HomeController::class, 'profile'], middlewares: [AuthMiddleware::class]);

$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register'], middlewares: [CsrfMiddleware::class]);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login'], middlewares: [CsrfMiddleware::class]);
$router->post('/logout', [AuthController::class, 'logout'], middlewares: [
     AuthMiddleware::class,
     CsrfMiddleware::class,
]);
