<?php

use Core\App;
use Core\Contracts\DB\Database;
use Core\Contracts\DB\QueryBuilder;
use Core\Contracts\Router;
use Core\Contracts\View;

function app(): App
{
    return App::getInstance();
}

function database()
{
    return app()->get(Database::class);
}

function router(): Router
{
    return app()->get(Router::class);
}

function render_view(string $view, array $data = []): string
{
    return app()->get(View::class)->view($view, $data);
}

function query_builder(): QueryBuilder
{
    return app()->get(QueryBuilder::class);
}
