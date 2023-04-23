<?php

use Core\App;
use Core\Contracts\AuthManager;
use Core\Exceptions\Handler;

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    error_reporting(E_ALL);
    set_error_handler('Core\Exceptions\Handler::errorHandler');

    $config = require __DIR__ . '/../config.php';
    $app    = new App(dirname(__DIR__), $config);
    $app->get(AuthManager::class)->checkAuth();
    require_once __DIR__ . '/../routes.php';
    return $app;
} catch (Throwable $e) {
    Handler::exceptionHandler($e);
}
