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
        $items    = Item::query()->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $total    = Item::query()->count();
        $lastPage = ceil($total / $perPage);
        return render_view('home', [
             'items' => $items,
             'meta'  => ['page' => $page, 'total' => $total, 'last_page' => $lastPage],
             'name'  => app()->getConfig('app_name'),
        ]);
    }

    //get cart list from cookie
    public function cart(): string
    {
        $cart  = $_COOKIE['cartItemList'] ?? '{}';
        $cart  = json_decode($cart, true);
        $cart  = array_filter($cart, static fn($quantity) => $quantity > 0);
        $items = [];
        if ($cart) {
            $items = Item::query()->whereIn('id', array_keys($cart))->limit(count($cart))->get();
        }

        return render_view('cart-list', compact('items'));
    }
}
