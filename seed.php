<?php

//TODO this part should be refactored

use App\Models\Item;
use App\Models\User;
use Core\App;

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

$app = new App(__DIR__, $config);

$user = User::findOne(['email' => 'mdaalvand@gmail.com']);
if (!$user) {
    $user = User::create([
         'first_name' => 'Mehdi',
         'last_name'  => 'daalvand',
         'email'      => 'mdaalvand@gmail.com',
         'password'   => password_hash('password', PASSWORD_DEFAULT),
    ]);
}

for ($i = 1; $i <= 100; $i++) {
    $items = Item::create([
         'title'       => generate_random_string(20),
         "description" => generate_random_string(100),
         "image"       => "https://fakeimg.pl/350x200/ff0000,128/000,255",
    ]);
}

