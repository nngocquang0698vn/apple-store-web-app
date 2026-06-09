<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Support\Str;

final class OrderCodeGenerator
{
    public static function generate(): string
    {
        do {
            $code = 'ORD-'.now()->format('ymd').'-'.strtoupper(Str::random(6));
        } while (Order::query()->where('order_code', $code)->exists());

        return $code;
    }
}
