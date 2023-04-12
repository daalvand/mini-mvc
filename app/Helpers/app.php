<?php

use Core\App;
use Core\Contracts\DB\Database;
use Core\Contracts\Router;

function app(): App
{
    return App::getInstance();
}

function database(){
    return app()->get(Database::class);
}
function router(): Router
{
    return app()->get(Router::class);
}
