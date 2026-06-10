<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ChangeOrderStatus
{
    public function execute(Order $order, OrderStatus $status): Order
    {
        if ($status === OrderStatus::Cancelled) {
            throw ValidationException::withMessages([
                'status' => ['Vui lòng dùng chức năng hủy đơn để hoàn tồn kho.'],
            ]);
        }

        return DB::transaction(function () use ($order, $status): Order {
            /** @var Order $lockedOrder */
            $lockedOrder = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $currentStatus = $lockedOrder->status;

            if ($currentStatus->isTerminal()) {
                throw ValidationException::withMessages([
                    'status' => ['Đơn hàng đã ở trạng thái cuối, không thể cập nhật.'],
                ]);
            }

            if (! $currentStatus->canTransitionTo($status)) {
                throw ValidationException::withMessages([
                    'status' => ["Không thể chuyển từ {$currentStatus->label()} sang {$status->label()}."],
                ]);
            }

            $lockedOrder->status = $status;

            if ($status === OrderStatus::Completed) {
                $lockedOrder->completed_at = now();
            }

            $lockedOrder->save();

            return $lockedOrder->fresh(['user', 'items']);
        });
    }
}
