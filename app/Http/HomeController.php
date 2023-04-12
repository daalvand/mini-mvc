<?php

namespace App\Http;

use Core\Contracts\Http\Controller;

class HomeController implements Controller
{
    public function index(): string
    {
        return 'home index';
    }
}
