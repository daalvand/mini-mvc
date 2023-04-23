<?php

namespace App\Http\Middlewares;

use Core\App;
use Core\Contracts\AuthManager;
use Core\Contracts\Http\Request;
use Core\Contracts\Http\Response;
use Core\Contracts\Http\Middleware;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request): void
    {
        if (App::getInstance()->get(AuthManager::class)->isGuest()) {
            App::getInstance()->get(Response::class)->redirect('/login');
        }
    }
}
