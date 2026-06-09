<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guest_can_add_item_to_cart(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();

        $response = $this->post(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(1, app(CartService::class)->count());
    }

    public function test_adding_same_variant_merges_quantity(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 10]);

        $this->post(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->post(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->assertEquals(3, app(CartService::class)->count());
    }

    public function test_cannot_add_more_than_stock(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 2]);

        $response = $this->from(route('products.show', $variant->product))
            ->post(route('cart.items.store'), [
                'variant_id' => $variant->id,
                'quantity' => 3,
            ]);

        $response->assertRedirect(route('products.show', $variant->product));
        $response->assertSessionHasErrors('quantity');
        $this->assertEquals(0, app(CartService::class)->count());
    }

    public function test_cannot_add_inactive_variant(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['is_active' => false]);

        $response = $this->post(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('variant_id');
        $this->assertEquals(0, app(CartService::class)->count());
    }

    public function test_client_submitted_price_is_ignored(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 25_990_000]);

        $this->post(route('cart.items.store'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
            'sale_price' => 1,
            'unit_price' => 1,
            'product_name' => 'Fake product',
        ]);

        $line = app(CartService::class)->getItems()->first();

        $this->assertNotNull($line);
        $this->assertSame(25_990_000, $line['unit_price']);
    }

    public function test_cart_index_shows_items_and_subtotal(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5, 'sale_price' => 10_000_000]);

        app(CartService::class)->add($variant->id, 2);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('iPhone 16 Pro', false);
        $response->assertSee('IP16P-BLK-128', false);
        $response->assertSee('20.000.000', false);
        $response->assertSee('Giỏ hàng (2)', false);
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        app(CartService::class)->add($variant->id, 1);

        $response = $this->patch(route('cart.items.update', $variant), [
            'quantity' => 3,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');
        $this->assertEquals(3, app(CartService::class)->count());
    }

    public function test_can_remove_cart_item(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        app(CartService::class)->add($variant->id, 2);

        $response = $this->delete(route('cart.items.destroy', $variant));

        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(0, app(CartService::class)->count());
    }

    public function test_can_clear_cart(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->firstOrFail();
        $variant->update(['stock_quantity' => 5]);

        app(CartService::class)->add($variant->id, 2);

        $response = $this->delete(route('cart.destroy'));

        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(0, app(CartService::class)->count());
    }

    public function test_empty_cart_page_renders(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Giỏ hàng trống', false);
    }
}
