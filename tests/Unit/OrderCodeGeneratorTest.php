<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Support\OrderCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCodeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_unique_order_code(): void
    {
        $this->seed();

        $code = OrderCodeGenerator::generate();

        $this->assertStringStartsWith('ORD-', $code);
        $this->assertSame(0, Order::query()->where('order_code', $code)->count());

        $secondCode = OrderCodeGenerator::generate();

        $this->assertNotSame($code, $secondCode);
    }
}
