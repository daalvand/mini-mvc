<?php


use Core\Contracts\App;
use Core\Exceptions\Handler;

try {
    /** @var App $app */
    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->run();
} catch (Throwable $e) {
    Handler::exceptionHandler($e);
}
