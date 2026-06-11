<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->admin = User::query()->where('email', 'admin@istore.test')->firstOrFail();
    }

    public function test_guest_cannot_access_admin_orders(): void
    {
        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_orders(): void
    {
        $customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();

        $this->actingAs($customer)->get(route('admin.orders.index'))->assertRedirect(route('home'));
    }

    public function test_admin_can_list_orders(): void
    {
        $order = Order::query()->firstOrFail();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertOk();
        $response->assertSee($order->order_code, false);
    }

    public function test_admin_can_filter_orders_by_code(): void
    {
        $order = Order::query()->firstOrFail();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', [
            'q' => $order->order_code,
        ]));

        $response->assertOk();
        $response->assertSee($order->order_code, false);
    }

    public function test_admin_can_filter_orders_by_status(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', [
            'status' => OrderStatus::Pending->value,
        ]));

        $response->assertOk();
        $response->assertSee(OrderStatus::Pending->label(), false);
    }

    public function test_admin_can_view_order_detail(): void
    {
        $order = Order::query()->with('items')->firstOrFail();
        $item = $order->items->first();
        $this->assertNotNull($item);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));

        $response->assertOk();
        $response->assertSee($order->order_code, false);
        $response->assertSee($item->product_name, false);
        $response->assertSee($order->user?->email ?? '', false);
    }

    public function test_admin_can_advance_order_status(): void
    {
        $order = Order::query()->where('status', OrderStatus::Pending)->firstOrFail();

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status.update', $order), [
            'status' => OrderStatus::Confirmed->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame(OrderStatus::Confirmed, $order->fresh()->status);
    }

    public function test_admin_cannot_skip_order_status(): void
    {
        $order = Order::query()->where('status', OrderStatus::Pending)->firstOrFail();

        $response = $this->actingAs($this->admin)->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status.update', $order), [
                'status' => OrderStatus::Completed->value,
            ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHasErrors('status');
        $this->assertSame(OrderStatus::Pending, $order->fresh()->status);
    }

    public function test_admin_cannot_update_terminal_order_status(): void
    {
        $order = Order::query()->where('status', OrderStatus::Completed)->firstOrFail();

        $response = $this->actingAs($this->admin)->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status.update', $order), [
                'status' => OrderStatus::Shipping->value,
            ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHasErrors('status');
    }

    public function test_admin_cancel_restock_inventory_once(): void
    {
        $customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();
        $variant = ProductVariant::query()->firstOrFail();
        $stockBeforeOrder = 20;
        $variant->update(['stock_quantity' => $stockBeforeOrder]);

        $order = Order::factory()->for($customer)->create([
            'status' => OrderStatus::Pending,
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => 'Test product',
            'sku' => $variant->sku,
            'color_name' => 'Đen',
            'storage_label' => '128 GB',
            'unit_price' => 10_000_000,
            'quantity' => 2,
            'line_total' => 20_000_000,
        ]);

        $variant->decrement('stock_quantity', 2);
        $this->assertSame(18, $variant->fresh()->stock_quantity);

        $this->actingAs($this->admin)->post(route('admin.orders.cancel', $order))->assertRedirect();
        $this->assertSame(20, $variant->fresh()->stock_quantity);
        $this->assertSame(OrderStatus::Cancelled, $order->fresh()->status);

        $this->actingAs($this->admin)->post(route('admin.orders.cancel', $order))->assertRedirect();
        $this->assertSame(20, $variant->fresh()->stock_quantity);
    }

    public function test_admin_cannot_cancel_shipping_order(): void
    {
        $order = Order::query()->where('status', OrderStatus::Shipping)->firstOrFail();

        $response = $this->actingAs($this->admin)->from(route('admin.orders.show', $order))
            ->post(route('admin.orders.cancel', $order));

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHasErrors('order');
        $this->assertSame(OrderStatus::Shipping, $order->fresh()->status);
    }
}
