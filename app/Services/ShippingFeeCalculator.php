<?php

namespace App\Services;

final class ShippingFeeCalculator
{
    public static function calculate(int $subtotal): int
    {
        $threshold = (int) config('store.shipping.free_threshold', 0);
        $fee = (int) config('store.shipping.fee', 0);

        if ($subtotal <= 0) {
            return 0;
        }

        return $subtotal >= $threshold ? 0 : $fee;
    }
}
