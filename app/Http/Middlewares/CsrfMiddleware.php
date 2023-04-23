<?php

namespace App\Http\Middlewares;

use Core\Contracts\Http\Request;
use Core\Contracts\Middleware;
use Core\Exceptions\ForbiddenException;

class CsrfMiddleware implements Middleware
{
    /**
     * @throws ForbiddenException
     */
    public function handle(Request $request): void
    {
        $inputs       = request()->getBody();
        $inputValue   = $inputs['csrf_token'] ?? null;
        $sessionValue = session()->getTemp('csrf_token');
        if (!$inputValue || $inputValue !== $sessionValue) {
            throw new ForbiddenException("Invalid csrf token!");
        }
    }
}
