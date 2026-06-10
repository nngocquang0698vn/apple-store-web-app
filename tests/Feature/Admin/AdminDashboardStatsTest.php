<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\AdminDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->admin = User::query()->where('email', 'admin@istore.test')->firstOrFail();
    }

    public function test_dashboard_displays_real_statistics(): void
    {
        $activeProducts = Product::query()->where('is_active', true)->count();
        $pendingOrders = Order::query()->where('status', OrderStatus::Pending)->count();
        $completedRevenue = (int) Order::query()
            ->where('status', OrderStatus::Completed)
            ->sum('total_amount');
        $latestOrder = Order::query()->latest()->firstOrFail();

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Đơn hàng mới nhất', false);
        $response->assertSee('Biến thể sắp hết hàng', false);
        $response->assertSee((string) $activeProducts, false);
        $response->assertSee((string) $pendingOrders, false);
        $response->assertSee(number_format($completedRevenue, 0, ',', '.'), false);
        $response->assertSee($latestOrder->order_code, false);
        $response->assertDontSee('Trạng thái dự án', false);
    }

    public function test_dashboard_service_returns_expected_keys(): void
    {
        $stats = app(AdminDashboardService::class)->build();

        $this->assertArrayHasKey('active_products', $stats);
        $this->assertArrayHasKey('customers', $stats);
        $this->assertArrayHasKey('total_orders', $stats);
        $this->assertArrayHasKey('pending_orders', $stats);
        $this->assertArrayHasKey('completed_revenue', $stats);
        $this->assertArrayHasKey('latest_orders', $stats);
        $this->assertArrayHasKey('low_stock_variants', $stats);
        $this->assertGreaterThan(0, $stats['active_products']);
        $this->assertSame(5, $stats['customers']);
        $this->assertSame(10, $stats['total_orders']);
    }

    public function test_low_stock_variants_respect_threshold(): void
    {
        $threshold = (int) config('store.low_stock_threshold', 5);

        ProductVariant::query()->update(['stock_quantity' => 100]);

        $lowVariant = ProductVariant::query()->with('product')->firstOrFail();
        $lowVariant->update(['stock_quantity' => $threshold, 'is_active' => true]);
        $lowVariant->product?->update(['is_active' => true]);

        $stats = app(AdminDashboardService::class)->build();

        $this->assertTrue(
            $stats['low_stock_variants']->contains(fn (ProductVariant $variant): bool => $variant->id === $lowVariant->id),
        );
    }
}
