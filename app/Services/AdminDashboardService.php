<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class AdminDashboardService
{
    private const LATEST_ORDERS_LIMIT = 8;

    private const LOW_STOCK_LIMIT = 10;

    /**
     * @return array{
     *     active_products: int,
     *     customers: int,
     *     total_orders: int,
     *     pending_orders: int,
     *     completed_revenue: int,
     *     latest_orders: Collection<int, Order>,
     *     low_stock_variants: Collection<int, ProductVariant>,
     *     low_stock_threshold: int,
     * }
     */
    public function build(): array
    {
        $lowStockThreshold = (int) config('store.low_stock_threshold', 5);

        return [
            'active_products' => Product::query()->where('is_active', true)->count(),
            'customers' => User::query()->where('role', UserRole::Customer)->count(),
            'total_orders' => Order::query()->count(),
            'pending_orders' => Order::query()->where('status', OrderStatus::Pending)->count(),
            'completed_revenue' => (int) Order::query()
                ->where('status', OrderStatus::Completed)
                ->sum('total_amount'),
            'latest_orders' => Order::query()
                ->with('user')
                ->latest()
                ->limit(self::LATEST_ORDERS_LIMIT)
                ->get(),
            'low_stock_variants' => ProductVariant::query()
                ->where('is_active', true)
                ->where('stock_quantity', '<=', $lowStockThreshold)
                ->whereHas('product', fn ($query) => $query->where('is_active', true))
                ->with(['product'])
                ->orderBy('stock_quantity')
                ->orderBy('sku')
                ->limit(self::LOW_STOCK_LIMIT)
                ->get(),
            'low_stock_threshold' => $lowStockThreshold,
        ];
    }
}
