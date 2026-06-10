<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Orders\CancelOrder;
use App\Actions\Orders\ChangeOrderStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const PER_PAGE = 15;

    public function index(OrderIndexRequest $request): View
    {
        $filters = $request->filters();

        $orders = Order::query()
            ->with(['user'])
            ->withCount('items')
            ->when(
                filled($filters['q'] ?? null),
                fn (Builder $query) => $query->where('order_code', 'like', '%'.trim($filters['q']).'%'),
            )
            ->when(
                filled($filters['status'] ?? null),
                fn (Builder $query) => $query->where('status', $filters['status']),
            )
            ->when(
                filled($filters['date_from'] ?? null),
                fn (Builder $query) => $query->whereDate('created_at', '>=', $filters['date_from']),
            )
            ->when(
                filled($filters['date_to'] ?? null),
                fn (Builder $query) => $query->whereDate('created_at', '<=', $filters['date_to']),
            )
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'filters' => $filters,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items']);

        return view('admin.orders.show', [
            'order' => $order,
            'nextStatuses' => $order->status->allowedTransitions(),
        ]);
    }

    public function updateStatus(
        UpdateOrderStatusRequest $request,
        Order $order,
        ChangeOrderStatus $changeOrderStatus,
    ): RedirectResponse {
        try {
            $changeOrderStatus->execute($order, $request->status());
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function cancel(Order $order, CancelOrder $cancelOrder): RedirectResponse
    {
        try {
            $cancelOrder->execute($order);
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        return back()->with('success', 'Đã hủy đơn hàng và hoàn tồn kho.');
    }
}
