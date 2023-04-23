<?php

namespace App\Http;

use App\Models\Item;
use Core\Contracts\Http\Controller;
use Core\Contracts\Http\Request;
use Core\Contracts\Http\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $page     = (int)$request->get('page', 1);
        $perPage  = (int)$request->get('per_page', 6);
        $items    = Item::query()->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $total    = Item::query()->count();
        $lastPage = ceil($total / $perPage);
        return response()->withView('home', [
             'items'      => $items,
             'pagination' => ['page' => $page, 'total' => $total, 'last_page' => $lastPage],
             'name'       => app()->getConfig('app_name'),
        ]);
    }

    //get cart list from cookie
    public function cart(Request $request): Response
    {
        $cart  = $request->cookie('cartItemList', '{}');
        $cart  = json_decode($cart, true);
        $cart  = array_filter($cart, static fn($quantity) => $quantity > 0);
        $items = [];
        if ($cart) {
            $items = Item::query()->whereIn('id', array_keys($cart))->limit(count($cart))->get();
        }

        return response()->withView('cart-list', ['items' => $items]);
    }
}
