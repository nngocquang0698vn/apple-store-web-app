<?php

namespace Tests\Unit;

use App\Models\ProductVariant;
use App\Services\CartService;
use App\Support\CartSummaryPresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartSummaryPresenterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_presenter_includes_formatted_money_and_items(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 12_345_678]);

        $cart = app(CartService::class);
        $cart->add($variant->id, 2);

        $summary = CartSummaryPresenter::fromCart($cart, $variant->id, 2);

        $this->assertSame(2, $summary['cart_count']);
        $this->assertSame(24_691_356, $summary['cart_subtotal']);
        $this->assertSame('12.345.678 ₫', $summary['formatted']['unit_price']);
        $this->assertSame('24.691.356 ₫', $summary['formatted']['line_subtotal']);
        $this->assertCount(1, $summary['items']);
        $this->assertTrue($summary['items'][0]['is_available']);
    }
}
