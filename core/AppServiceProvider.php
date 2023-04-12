<?php

namespace Core;

use Core\Contracts\Router as RouterContract;
use Core\Contracts\ServiceProvider;
use Core\Contracts\App;

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
    }
}
