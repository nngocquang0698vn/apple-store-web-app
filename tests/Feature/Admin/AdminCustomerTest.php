<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCustomerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->admin = User::query()->where('email', 'admin@istore.test')->firstOrFail();
    }

    public function test_guest_cannot_access_admin_customers(): void
    {
        $this->get(route('admin.customers.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_list_customers(): void
    {
        $customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();

        $response = $this->actingAs($this->admin)->get(route('admin.customers.index'));

        $response->assertOk();
        $response->assertSee($customer->name, false);
        $response->assertSee($customer->email, false);
        $response->assertDontSee('admin@istore.test', false);
    }

    public function test_admin_can_search_customers(): void
    {
        $customer = User::query()->where('email', 'customer2@istore.test')->firstOrFail();

        $response = $this->actingAs($this->admin)->get(route('admin.customers.index', [
            'q' => $customer->phone,
        ]));

        $response->assertOk();
        $response->assertSee($customer->email, false);
    }

    public function test_admin_can_view_customer_detail(): void
    {
        $customer = User::query()->where('email', 'customer1@istore.test')->firstOrFail();

        $response = $this->actingAs($this->admin)->get(route('admin.customers.show', $customer));

        $response->assertOk();
        $response->assertSee($customer->name, false);
        $response->assertSee($customer->email, false);
    }

    public function test_admin_cannot_view_admin_user_as_customer(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.customers.show', $this->admin))
            ->assertNotFound();
    }

    public function test_admin_can_block_customer(): void
    {
        $customer = User::query()->where('email', 'customer3@istore.test')->firstOrFail();

        $response = $this->actingAs($this->admin)->patch(route('admin.customers.status.update', $customer), [
            'status' => UserStatus::Blocked->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame(UserStatus::Blocked, $customer->fresh()->status);
    }

    public function test_admin_cannot_update_status_of_admin_account(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.customers.status.update', $this->admin), [
                'status' => UserStatus::Blocked->value,
            ])
            ->assertNotFound();
    }

    public function test_customer_list_excludes_admin_accounts(): void
    {
        $this->assertSame(
            0,
            User::query()->where('role', UserRole::Customer)->where('email', 'admin@istore.test')->count(),
        );
    }
}
