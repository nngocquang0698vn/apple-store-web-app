<?php

namespace App\Support;

final class VndMoney
{
    public static function format(int $amount): string
    {
        return number_format($amount, 0, ',', '.').' ₫';
    }
}
