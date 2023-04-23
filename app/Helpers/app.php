<?php

use Core\App;
use Core\Contracts\App as AppContract;
use Core\Contracts\AuthManager;
use Core\Contracts\DB\Database;
use Core\Contracts\DB\Migrator;
use Core\Contracts\DB\QueryBuilder;
use Core\Contracts\DB\Schema;
use Core\Contracts\Http\Request;
use Core\Contracts\Http\Response;
use Core\Contracts\Router;
use Core\Contracts\Session;
use Core\Contracts\View;

function app(): AppContract
{
    return App::getInstance();
}

function database(): Database
{
    return app()->get(Database::class);
}

function schema(): Schema
{
    return app()->get(Schema::class);
}

function migrator(): Migrator
{
    return app()->get(Migrator::class);
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

function view(): View
{
    return app()->get(View::class);
}

function session(): Session
{
    return app()->get(Session::class);
}

function response(): Response
{
    return app()->get(Response::class);
}

function request(): Request
{
    return app()->get(Request::class);
}

function auth(): AuthManager
{
    return app()->get(AuthManager::class);
}
