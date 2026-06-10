<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shipping fee (integer VND)
    |--------------------------------------------------------------------------
    */
    'shipping' => [
        'fee' => (int) env('SHIPPING_FEE', 30_000),
        'free_threshold' => (int) env('SHIPPING_FREE_THRESHOLD', 10_000_000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Low stock alert threshold (admin dashboard)
    |--------------------------------------------------------------------------
    */
    'low_stock_threshold' => (int) env('LOW_STOCK_THRESHOLD', 5),

];
