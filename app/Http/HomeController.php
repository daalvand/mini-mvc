<?php

namespace App\Http;

use Core\Contracts\Http\Controller;

class HomeController implements Controller
{
    public function index(): string
    {
        return render_view('home', ['name' => 'Mehdi']);
    }
}
