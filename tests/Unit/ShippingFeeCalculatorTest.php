<?php

namespace Tests\Unit;

use App\Services\ShippingFeeCalculator;
use Tests\TestCase;

class ShippingFeeCalculatorTest extends TestCase
{
    public function test_free_shipping_when_subtotal_meets_threshold(): void
    {
        $this->assertSame(0, ShippingFeeCalculator::calculate(10_000_000));
        $this->assertSame(0, ShippingFeeCalculator::calculate(15_000_000));
    }

    public function test_shipping_fee_when_subtotal_below_threshold(): void
    {
        $this->assertSame(30_000, ShippingFeeCalculator::calculate(9_999_999));
    }

    public function test_no_shipping_fee_for_empty_cart(): void
    {
        $this->assertSame(0, ShippingFeeCalculator::calculate(0));
    }
}
