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
        $cart  = $_COOKIE['cartItemList'] ?? '{}';
        $cart  = json_decode($cart, true);
        $cart  = array_filter($cart, static fn($quantity) => $quantity > 0);
        $items = ['data' => [], 'meta' => []];
        if ($cart) {
            $items = Item::paginate(['id' => array_keys($cart)], perPage: count($cart));
            foreach ($items['data'] as &$item) {
                $item->quantity = $cart[$item->id];
            }
        }


        return render_view('cart-list', [
             'items' => $items['data'],
             'meta'  => $items['meta'],
        ]);
    }
}
