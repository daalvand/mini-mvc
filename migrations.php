<?php

use Core\App;
use Core\Contracts\DB\Migrator;

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

$app = new App(__DIR__, $config);

$app->get(Migrator::class)->applyMigrations();
