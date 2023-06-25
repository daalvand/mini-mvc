<?php

namespace App\Http\Middlewares;

use Closure;
use Core\Contracts\Http\Middleware;
use Core\Contracts\Http\Request;
use Core\Exceptions\ForbiddenException;

class CsrfMiddleware implements Middleware
{
    /**
     * @throws ForbiddenException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $inputValue   = $request->post('csrf_token');
        $sessionValue = session()->csrfToken();
        if (!$inputValue || $inputValue !== $sessionValue) {
            throw new ForbiddenException("Invalid csrf token!");
        }
        return $next($request);
    }
}
