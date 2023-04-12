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
        return render_view('home', [
             'items' => $paginate['data'],
             'meta'  => $paginate['meta'],
             'name'  => app()->getConfig('app_name'),
        ]);
    }

    //get cart list from cookie
     public function cart(): string
     {
          $cart = $_COOKIE['cart'] ?? '[]';
          $cart = json_decode($cart, true);
          //todo implement cart list
     }
}
