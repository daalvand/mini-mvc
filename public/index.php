<?php


use Core\Contracts\App;
use Core\Exceptions\Handler;

/** @var App $app */
$app = require __DIR__ . '/../bootstrap/app.php';
Handler::bootstrap();
session()->start();
auth()->checkAuth();
$app->run();
