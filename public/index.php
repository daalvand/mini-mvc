<?php

use Core\App;
use Core\Contracts\AuthManager;

require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Exceptions\Handler::errorHandler');
set_exception_handler('Core\Exceptions\Handler::exceptionHandler');

$config = require __DIR__ . '/../config.php';
$app    = new App(dirname(__DIR__), $config);

require_once __DIR__ . '/../routes.php';

$app->get(AuthManager::class)->checkAuth();

$app->run();
