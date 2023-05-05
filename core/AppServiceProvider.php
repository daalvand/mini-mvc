<?php

namespace Core;

use Core\Contracts\App;
use Core\Contracts\AuthManager as AuthManagerContract;
use Core\Contracts\DB\Database as DatabaseContract;
use Core\Contracts\DB\Migrator as MigratorContract;
use Core\Contracts\DB\QueryBuilder as QueryBuilderContract;
use Core\Contracts\DB\Schema as SchemaContract;
use Core\Contracts\Http\Request as RequestContract;
use Core\Contracts\Http\Response as ResponseContract;
use Core\Contracts\Router as RouterContract;
use Core\Contracts\ServiceProvider;
use Core\Contracts\Session as SessionContract;
use Core\Contracts\View as ViewContract;
use Core\DB\Database;
use Core\DB\Migrator;
use Core\DB\ModelQueryBuilder;
use Core\DB\QueryBuilder;
use Core\DB\Schema\Schema;
use Core\Http\Request;
use Core\Http\Response;

class AppServiceProvider implements ServiceProvider
{
    public function __construct(protected App $app)
    {
    }

    public function register(): void
    {
        $this->app->singleton(RouterContract::class, function () {
            return new Router(
                 request: $this->app->get(RequestContract::class),
            );
        });

        $this->app->singleton(DatabaseContract::class, function (App $app) {
            return new Database(config: $app->getConfig('db'));
        });

        $this->app->singleton(MigratorContract::class, function (App $app) {
            return new Migrator(
                 builder: new QueryBuilder($app->get(DatabaseContract::class)),
                 basePath: $app->basePath()
            );
        });

        $this->app->singleton(ViewContract::class, function (App $app) {
            return new View(
                 config: $app->getConfig('views'),
                 basePath: $app->basePath(),
            );
        });

        $this->app->singleton(SessionContract::class, function () {
            return new Session();
        });

        $this->app->singleton(AuthManagerContract::class, function (App $app) {
            return new AuthManager(
                 session: $app->get(SessionContract::class),
                 configs: $app->getConfig('auth'),
            );
        });

        $this->app->singleton(SchemaContract::class, function (App $app) {
            return new Schema(db: $app->get(DatabaseContract::class));
        });

        $this->app->singleton(RequestContract::class, function () {
            return new Request();
        });

        $this->app->bind(ResponseContract::class, function () {
            return new Response();
        });

        $this->app->bind(QueryBuilderContract::class, function (App $app) {
            return new QueryBuilder($app->get(DatabaseContract::class));
        });

        $this->app->bind(ModelQueryBuilder::class, function (App $app) {
            return new ModelQueryBuilder($app->get(DatabaseContract::class));
        });
    }
}
