<?php

namespace Core;

use Core\Contracts\DB\Database as DatabaseContract;
use Core\Contracts\Router as RouterContract;
use Core\Contracts\ServiceProvider;
use Core\Contracts\App;
use Core\DB\Database;
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
    }
}
