<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAjaxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_add_to_cart_returns_json_summary(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 25_990_000]);

        $response = $this->postJson(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.variant_id', $variant->id);
        $response->assertJsonPath('data.quantity', 2);
        $response->assertJsonPath('data.unit_price', 25_990_000);
        $response->assertJsonPath('data.line_subtotal', 51_980_000);
        $response->assertJsonPath('data.cart_count', 2);
        $response->assertJsonPath('data.cart_subtotal', 51_980_000);
        $response->assertJsonPath('data.shipping_fee', 0);
        $response->assertJsonPath('data.grand_total', 51_980_000);
        $response->assertJsonPath('data.stock_quantity', 5);
    }

    public function test_add_to_cart_json_validation_error(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 1]);

        $response = $this->postJson(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 5,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test_client_submitted_price_is_ignored_in_json_response(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 20_000_000]);

        $response = $this->postJson(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
            'sale_price' => 1,
            'unit_price' => 1,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.unit_price', 20_000_000);
    }

    public function test_update_cart_item_returns_json_summary(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 10_000_000]);

        app(CartService::class)->add($variant->id, 1);

        $response = $this->patchJson(route('cart.items.update', $variant), [
            'quantity' => 3,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.quantity', 3);
        $response->assertJsonPath('data.line_subtotal', 30_000_000);
        $response->assertJsonPath('data.cart_count', 3);
    }

    public function test_remove_cart_item_returns_json_summary(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        app(CartService::class)->add($variant->id, 2);

        $response = $this->deleteJson(route('cart.items.destroy', $variant));

        $response->assertOk();
        $response->assertJsonPath('data.cart_count', 0);
        $response->assertJsonPath('data.cart_subtotal', 0);
        $response->assertJsonPath('data.grand_total', 0);
    }

    public function test_cart_summary_endpoint_returns_totals(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 5_000_000]);

        app(CartService::class)->add($variant->id, 1);

        $response = $this->getJson(route('cart.summary'));

        $response->assertOk();
        $response->assertJsonPath('data.cart_count', 1);
        $response->assertJsonPath('data.cart_subtotal', 5_000_000);
        $response->assertJsonPath('data.shipping_fee', 30_000);
        $response->assertJsonPath('data.grand_total', 5_030_000);
    }

    public function test_cart_summary_returns_conflict_when_item_not_purchasable(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        app(CartService::class)->add($variant->id, 3);
        $variant->update(['stock_quantity' => 1]);

        $response = $this->getJson(route('cart.summary'));

        $response->assertStatus(409);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('data.cart_count', 3);
    }

    public function test_non_ajax_add_still_redirects(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        $response = $this->post(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
