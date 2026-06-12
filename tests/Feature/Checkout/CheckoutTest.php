<?php

namespace Tests\Feature\Checkout;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();
    }

    /**
     * @return array<string, string>
     */
    private function validShippingPayload(): array
    {
        return [
            'receiver_name' => 'Nguyễn Văn A',
            'receiver_phone' => '0901234567',
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Thành phố Thủ Đức',
            'ward' => 'Phường Linh Trung',
            'address_line' => 'Số 1 đường ABC',
            'note' => 'Giao giờ hành chính',
        ];
    }

    public function test_guest_cannot_access_checkout(): void
    {
        $this->get(route('checkout.create'))->assertRedirect(route('login'));
        $this->post(route('checkout.store'), $this->validShippingPayload())->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_checkout_page(): void
    {
        $variant = $this->prepareVariantInCart();

        $response = $this->actingAs($this->customer)->get(route('checkout.create'));

        $response->assertOk();
        $response->assertSee('Thanh toán', false);
        $response->assertSee($variant->product->name, false);
        $response->assertSee('data-checkout-summary', false);
    }

    public function test_empty_cart_redirects_from_checkout(): void
    {
        $response = $this->actingAs($this->customer)->get(route('checkout.create'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('warning');
    }

    public function test_successful_checkout_creates_order_and_clears_cart(): void
    {
        $variant = $this->prepareVariantInCart(stock: 10, salePrice: 12_000_000, quantity: 2);
        $initialStock = $variant->fresh()->stock_quantity;

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), array_merge(
            $this->validShippingPayload(),
            [
                'subtotal' => 1,
                'shipping_fee' => 1,
                'total_amount' => 1,
            ],
        ));

        $order = Order::query()->latest('id')->first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order));
        $response->assertSessionHas('success');

        $this->assertSame($this->customer->id, $order->user_id);
        $this->assertSame(OrderStatus::Pending, $order->status);
        $this->assertSame('cod', $order->payment_method);
        $this->assertSame(24_000_000, $order->subtotal);
        $this->assertSame(0, $order->shipping_fee);
        $this->assertSame(24_000_000, $order->total_amount);
        $this->assertSame(0, app(CartService::class)->count());

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'sku' => $variant->sku,
            'quantity' => 2,
            'unit_price' => 12_000_000,
            'line_total' => 24_000_000,
        ]);

        $this->assertSame($initialStock - 2, $variant->fresh()->stock_quantity);
    }

    public function test_checkout_rejects_insufficient_stock_and_rolls_back(): void
    {
        $variant = $this->prepareVariantInCart(stock: 5, quantity: 3);
        $variant->update(['stock_quantity' => 1]);

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), $this->validShippingPayload());

        $response->assertRedirect(route('checkout.create'));
        $response->assertSessionHasErrors('cart');
        $this->assertSame(1, $variant->fresh()->stock_quantity);
        $this->assertSame(3, app(CartService::class)->count());
        $this->assertDatabaseCount('orders', 10);
    }

    public function test_checkout_success_page_requires_order_ownership(): void
    {
        $order = Order::query()->firstOrFail();
        $otherCustomer = User::query()->where('email', 'customer2@istore.test')->firstOrFail();

        $this->actingAs($otherCustomer)
            ->get(route('checkout.success', $order))
            ->assertForbidden();
    }

    public function test_checkout_success_page_shows_order_for_owner(): void
    {
        $variant = $this->prepareVariantInCart();

        $this->actingAs($this->customer)->post(route('checkout.store'), $this->validShippingPayload());

        $order = Order::query()->where('user_id', $this->customer->id)->latest('id')->firstOrFail();

        $response = $this->actingAs($this->customer)->get(route('checkout.success', $order));

        $response->assertOk();
        $response->assertSee($order->order_code, false);
        $response->assertSee($variant->product->name, false);
    }

    public function test_checkout_uses_current_database_price(): void
    {
        $variant = $this->prepareVariantInCart(stock: 10, salePrice: 10_000_000);
        $variant->update(['sale_price' => 11_500_000]);

        $this->actingAs($this->customer)->post(route('checkout.store'), $this->validShippingPayload());

        $order = Order::query()->where('user_id', $this->customer->id)->latest('id')->firstOrFail();

        $this->assertSame(11_500_000, $order->subtotal);
        $this->assertSame(11_500_000, $order->items()->first()->unit_price);
    }

    public function test_order_items_store_snapshots(): void
    {
        $variant = $this->prepareVariantInCart(salePrice: 9_990_000);
        $productName = $variant->product->name;

        $this->actingAs($this->customer)->post(route('checkout.store'), $this->validShippingPayload());

        $item = OrderItem::query()->latest('id')->firstOrFail();

        $this->assertSame($productName, $item->product_name);
        $this->assertSame($variant->sku, $item->sku);
        $this->assertSame($variant->color?->name, $item->color_name);
        $this->assertSame($variant->storageOption?->label, $item->storage_label);
    }

    private function prepareVariantInCart(int $stock = 10, int $salePrice = 10_000_000, int $quantity = 1): ProductVariant
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BTI-128')->firstOrFail();
        $variant->update([
            'stock_quantity' => $stock,
            'sale_price' => $salePrice,
            'is_active' => true,
        ]);

        app(CartService::class)->clear();
        app(CartService::class)->add($variant->id, $quantity);

        return $variant->fresh(['product', 'color', 'storageOption']);
    }
}
