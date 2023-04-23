<?php

namespace App\Http;

use App\Models\Item;
use Core\Contracts\Http\Controller;
use Core\Contracts\Http\Request;
use Core\Contracts\Http\Response;

class ProfileController extends Controller
{

    public function profile(): Response
    {
        return response()->withView('profile/profile', ['user' => auth()->getUser()]);
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

        return response()->withView('profile/cart-list', ['items' => $items]);
    }
}
