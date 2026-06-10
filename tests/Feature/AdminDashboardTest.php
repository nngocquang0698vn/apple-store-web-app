<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_blocked_admin_cannot_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->blocked()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_admin_dashboard_renders_successfully(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard', false);
        $response->assertSee('Đơn hàng mới nhất', false);
    }
}
