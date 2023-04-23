<?php

namespace App\Http\Middlewares;

use Core\Contracts\Http\Request;
use Core\Contracts\Http\Middleware;
use Core\Exceptions\ForbiddenException;

class CsrfMiddleware implements Middleware
{
    /**
     * @throws ForbiddenException
     */
    public function handle(Request $request): void
    {
        $inputValue   = request()->post('csrf_token');
        $sessionValue = session()->csrfToken();
        if (!$inputValue || $inputValue !== $sessionValue) {
            throw new ForbiddenException("Invalid csrf token!");
        }
    }
}
