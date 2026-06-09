<?php

namespace Tests\Feature\Checkout;

use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutSummaryTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();
    }

    public function test_guest_cannot_access_checkout_summary(): void
    {
        $this->getJson(route('checkout.summary'))->assertUnauthorized();
    }

    public function test_checkout_summary_returns_canonical_totals(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 8_000_000]);

        app(CartService::class)->add($variant->id, 1);

        $response = $this->actingAs($this->customer)->getJson(route('checkout.summary'));

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.cart_count', 1);
        $response->assertJsonPath('data.cart_subtotal', 8_000_000);
        $response->assertJsonPath('data.shipping_fee', 30_000);
        $response->assertJsonPath('data.grand_total', 8_030_000);
        $response->assertJsonPath('data.can_checkout', true);
        $response->assertJsonPath('data.items.0.variant_id', $variant->id);
        $response->assertJsonPath('data.items.0.unit_price', 8_000_000);
        $response->assertJsonPath('data.items.0.line_subtotal', 8_000_000);
    }

    public function test_checkout_summary_returns_conflict_when_stock_changes(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        app(CartService::class)->add($variant->id, 3);
        $variant->update(['stock_quantity' => 1]);

        $response = $this->actingAs($this->customer)->getJson(route('checkout.summary'));

        $response->assertStatus(409);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('data.can_checkout', false);
        $response->assertJsonPath('data.items.0.is_purchasable', false);
    }

    public function test_checkout_summary_rejects_empty_cart(): void
    {
        $response = $this->actingAs($this->customer)->getJson(route('checkout.summary'));

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
    }
}
