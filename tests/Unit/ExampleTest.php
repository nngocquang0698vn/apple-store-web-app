<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_access_and_order_enums_have_expected_backing_values(): void
    {
        $this->assertSame('customer', UserRole::Customer->value);
        $this->assertSame('admin', UserRole::Admin->value);
        $this->assertSame('active', UserStatus::Active->value);
        $this->assertSame('blocked', UserStatus::Blocked->value);
        $this->assertSame('pending', OrderStatus::Pending->value);
        $this->assertSame('confirmed', OrderStatus::Confirmed->value);
        $this->assertSame('shipping', OrderStatus::Shipping->value);
        $this->assertSame('completed', OrderStatus::Completed->value);
        $this->assertSame('cancelled', OrderStatus::Cancelled->value);
    }
}
