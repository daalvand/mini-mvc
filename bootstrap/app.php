<?php

use Core\App;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config.php';
$app    = new App(dirname(__DIR__), $config);
require_once __DIR__ . '/../routes.php';
return $app;
