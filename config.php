<?php

use App\Models\User;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
     'app_name'   => $_ENV['APP_NAME'],
     'db'         => [
          'dsn'      => $_ENV['DB_DSN'],
          'username' => $_ENV['DB_USER'],
          'password' => $_ENV['DB_PASSWORD'],
     ],
];
