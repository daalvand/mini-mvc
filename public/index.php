<?php


use Core\Contracts\App;
use Core\Contracts\AuthManager;
use Core\Exceptions\Handler;

try {
    /** @var App $app */
    $app = require __DIR__ . '/../bootstrap/app.php';
    session()->start();
    auth()->checkAuth();
    $app->run();
} catch (Throwable $e) {
    Handler::exceptionHandler($e);
}
