<?php


use Core\Contracts\App;
use Core\Contracts\AuthManager;
use Core\Exceptions\Handler;

try {
    /** @var App $app */
    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->get(AuthManager::class)->checkAuth();
    $app->run();
} catch (Throwable $e) {
    Handler::exceptionHandler($e);
}
