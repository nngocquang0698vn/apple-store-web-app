<?php

namespace Tests\Feature\Account;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private User $otherCustomer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();
        $this->otherCustomer = User::query()->where('email', 'customer2@istore.test')->firstOrFail();
    }

    public function test_guest_cannot_access_order_history(): void
    {
        $this->get(route('account.orders.index'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_order_detail(): void
    {
        $order = Order::query()->where('user_id', $this->customer->id)->firstOrFail();

        $this->get(route('account.orders.show', $order))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_only_their_orders(): void
    {
        $ownOrder = Order::query()
            ->where('user_id', $this->customer->id)
            ->firstOrFail();

        $otherOrder = Order::query()
            ->where('user_id', $this->otherCustomer->id)
            ->firstOrFail();

        $response = $this->actingAs($this->customer)->get(route('account.orders.index'));

        $response->assertOk();
        $response->assertSee('Đơn hàng của tôi', false);
        $response->assertSee($ownOrder->order_code, false);
        $response->assertDontSee($otherOrder->order_code, false);
    }

    public function test_order_history_shows_status_badges(): void
    {
        $order = Order::query()
            ->where('user_id', $this->customer->id)
            ->where('status', OrderStatus::Pending)
            ->firstOrFail();

        $response = $this->actingAs($this->customer)->get(route('account.orders.index'));

        $response->assertOk();
        $response->assertSee($order->order_code, false);
        $response->assertSee(OrderStatus::Pending->label(), false);
    }

    public function test_order_history_is_paginated(): void
    {
        Order::query()->where('user_id', $this->customer->id)->delete();

        Order::factory()
            ->count(11)
            ->for($this->customer)
            ->create();

        $firstPage = $this->actingAs($this->customer)->get(route('account.orders.index'));

        $firstPage->assertOk();
        $firstPage->assertSee('page=2', false);

        $secondPage = $this->actingAs($this->customer)->get(route('account.orders.index', ['page' => 2]));

        $secondPage->assertOk();
    }

    public function test_empty_order_history_shows_empty_state(): void
    {
        Order::query()->where('user_id', $this->customer->id)->delete();

        $response = $this->actingAs($this->customer)->get(route('account.orders.index'));

        $response->assertOk();
        $response->assertSee('Chưa có đơn hàng', false);
        $response->assertSee(route('products.index'), false);
    }

    public function test_owner_can_view_order_detail_with_snapshots(): void
    {
        $order = Order::query()
            ->where('user_id', $this->customer->id)
            ->with('items')
            ->firstOrFail();

        $item = $order->items->first();
        $this->assertNotNull($item);

        $response = $this->actingAs($this->customer)->get(route('account.orders.show', $order));

        $response->assertOk();
        $response->assertSee($order->order_code, false);
        $response->assertSee($order->status->label(), false);
        $response->assertSee($order->receiver_name, false);
        $response->assertSee($item->product_name, false);
        $response->assertSee($item->sku, false);
        $response->assertSee((string) $item->quantity, false);
    }

    public function test_other_customer_cannot_view_order_detail(): void
    {
        $order = Order::query()
            ->where('user_id', $this->customer->id)
            ->firstOrFail();

        $this->actingAs($this->otherCustomer)
            ->get(route('account.orders.show', $order))
            ->assertForbidden();
    }

    public function test_blocked_customer_cannot_access_order_pages(): void
    {
        $blockedCustomer = User::factory()->blocked()->create();

        $this->actingAs($blockedCustomer)
            ->get(route('account.orders.index'))
            ->assertRedirect(route('login'));
    }

    public function test_order_detail_displays_snapshot_values_not_live_product_data(): void
    {
        $order = Order::factory()
            ->for($this->customer)
            ->create([
                'subtotal' => 20_000_000,
                'shipping_fee' => 30_000,
                'total_amount' => 20_030_000,
            ]);

        $variant = ProductVariant::query()->firstOrFail();

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => 'iPhone Snapshot Test',
            'sku' => 'SNAPSHOT-SKU-001',
            'color_name' => 'Đen',
            'storage_label' => '256 GB',
            'unit_price' => 20_000_000,
            'quantity' => 1,
            'line_total' => 20_000_000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('account.orders.show', $order));

        $response->assertOk();
        $response->assertSee('iPhone Snapshot Test', false);
        $response->assertSee('SNAPSHOT-SKU-001', false);
        $response->assertSee('Đen', false);
        $response->assertSee('256 GB', false);
        $response->assertSee('20.030.000 ₫', false);
    }
}
