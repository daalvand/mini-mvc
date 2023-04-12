<?php

namespace Core;

use Core\Contracts\DB\Database as DatabaseContract;
use Core\Contracts\DB\Migrator as MigratorContract;
use Core\Contracts\Router as RouterContract;
use Core\Contracts\ServiceProvider;
use Core\Contracts\App;
use Core\Contracts\View as ViewContract;
use Core\DB\Database;
use Core\DB\Migrator;

class AppServiceProvider implements ServiceProvider
{
    public function __construct(protected App $app)
    {
    }

    public function register(): void
    {
        $this->app->set(RouterContract::class, function (App $app) {
            return new Router();
        });


        $this->app->set(DatabaseContract::class, function (App $app) {
            return new Database(config: $app->getConfig('db'));
        });

        $this->app->set(MigratorContract::class, function (App $app) {
            return new Migrator(
                 database: $app->get(DatabaseContract::class),
                 basePath: $app->basePath()
            );
        });

        $this->app->set(ViewContract::class, function (App $app) {
            return new View(
                 config: $app->getConfig('views'),
                 basePath: $app->basePath(),
            );
        });
    }
}
