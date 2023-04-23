<?php

namespace Core\Contracts\Http;

interface Middleware
{
    public function handle(Request $request);
}
