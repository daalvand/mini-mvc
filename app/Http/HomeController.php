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
}
