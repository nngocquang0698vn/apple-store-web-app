<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CancelOrder
{
    public function execute(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            /** @var Order $lockedOrder */
            $lockedOrder = Order::query()
                ->with('items')
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status === OrderStatus::Cancelled) {
                return $lockedOrder;
            }

            if (! $lockedOrder->status->canBeCancelled()) {
                throw ValidationException::withMessages([
                    'order' => ['Chỉ có thể hủy đơn ở trạng thái chờ xác nhận hoặc đã xác nhận.'],
                ]);
            }

            $variantIds = $lockedOrder->items
                ->pluck('product_variant_id')
                ->filter()
                ->unique()
                ->values()
                ->all();

            /** @var Collection<int, ProductVariant> $variants */
            $variants = ProductVariant::query()
                ->whereIn('id', $variantIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($lockedOrder->items as $item) {
                if ($item->product_variant_id === null) {
                    continue;
                }

                $variant = $variants->get($item->product_variant_id);

                if ($variant !== null) {
                    $variant->increment('stock_quantity', $item->quantity);
                }
            }

            $lockedOrder->status = OrderStatus::Cancelled;
            $lockedOrder->cancelled_at = now();
            $lockedOrder->save();

            return $lockedOrder->fresh(['user', 'items']);
        });
    }
}
