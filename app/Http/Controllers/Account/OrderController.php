<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('account.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load('items');

        return view('account.orders.show', [
            'order' => $order,
        ]);
    }
}
