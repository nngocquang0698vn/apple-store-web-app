<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_subtotal_uses_database_prices(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 12_500_000]);

        $cart = app(CartService::class);
        $cart->add($variant->id, 2);

        $this->assertSame(25_000_000, $cart->subtotal());
    }

    public function test_update_rejects_quantity_above_stock(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 2]);

        $cart = app(CartService::class);
        $cart->add($variant->id, 1);

        $this->expectException(ValidationException::class);

        $cart->update($variant->id, 5);
    }

    public function test_add_rejects_inactive_product(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        Product::query()->whereKey($variant->product_id)->update(['is_active' => false]);

        $this->expectException(ValidationException::class);

        app(CartService::class)->add($variant->id, 1);
    }
}
