<?php

namespace Core\Contracts\Http;

use Closure;

interface Middleware
{
    public function handle(Request $request, Closure $next): mixed;
}
