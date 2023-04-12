<?php

namespace App\Http;

use App\Models\Item;
use Core\Contracts\Http\Controller;

class HomeController implements Controller
{
    public function index(): string
    {
        $page     = (int)($_GET['page'] ?? 1);
        $perPage  = (int)($_GET['per_page'] ?? 6);
        $paginate = Item::paginate([], page: $page, perPage: $perPage);
        $meta     = $paginate['meta'];
        return render_view('home', [
             'items' => $paginate['data'],
             'meta'  => $paginate['meta'],
             'name'  => app()->getConfig('app_name'),
        ]);
    }
}
