<?php

namespace App\Http\Middlewares;

use Closure;
use Core\Contracts\Http\Middleware;
use Core\Contracts\Http\Request;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->isGuest()) {
            return response()->redirect('/login');
        }
        return $next($request);
    }
}
