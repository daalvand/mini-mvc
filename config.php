<?php

use App\Models\User;
use Core\Validator\Rules\Confirmed;
use Core\Validator\Rules\Email;
use Core\Validator\Rules\Max;
use Core\Validator\Rules\Min;
use Core\Validator\Rules\Required;
use Core\Validator\Rules\Same;
use Core\Validator\Rules\Unique;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
     'app_name'  => $_ENV['APP_NAME'],
     'db'        => [
          'dsn'      => $_ENV['DB_DSN'],
          'username' => $_ENV['DB_USER'],
          'password' => $_ENV['DB_PASSWORD'],
     ],
     'views'     => [
          'path'       => 'views',
          'cache_path' => 'storage/cache/views',
          'cacheable'  => $_ENV['VIEW_CACHEABLE'] === 'true',
     ],
     'auth'      => [
          'user' => User::class,
     ],
     'log'       => [
          'path' => 'storage/logs',
     ],
     'validator' => [
          'rules' => [
               'confirmed' => Confirmed::class,
               'email'     => Email::class,
               'max'       => Max::class,
               'min'       => Min::class,
               'required'  => Required::class,
               'unique'    => Unique::class,
          ],
     ],
     'csrf'      => [
          'key' => 'csrf_token',
          'ttl' => 15 * 60,// 15 minutes
     ],
];
