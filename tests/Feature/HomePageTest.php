<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Cửa hàng điện thoại Apple cho đồ án web', false);
        $response->assertDontSee('Vào khu vực quản trị', false);
        $response->assertDontSee(route('admin.dashboard'), false);
        $response->assertSee('data-home-showcase', false);
        $response->assertSee('data-flash-container', false);

        if (is_file(storage_path('app/public/products/demo/iphone-15-black.webp'))) {
            $response->assertSee('/storage/products/demo/', false);
            $response->assertSee('iPhone 16 Pro', false);
            $response->assertSee('19.990.000', false);
        } else {
            $response->assertSee('images/placeholders/product-placeholder.svg', false);
        }
    }

    public function test_home_page_renders_flash_message(): void
    {
        $response = $this
            ->withSession(['success' => 'Thao tác thành công.'])
            ->get(route('home'));

        $response->assertOk();
        $response->assertSee('Thao tác thành công.', false);
        $response->assertSee('data-flash-auto-dismiss', false);
        $response->assertDontSee('data-flash-persistent', false);
    }

    public function test_home_page_shows_admin_shortcut_for_administrator(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertOk();
        $response->assertSee('Vào khu vực quản trị', false);
        $response->assertSee(route('admin.dashboard'), false);
    }

    public function test_home_page_hides_admin_shortcut_for_customer(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Vào khu vực quản trị', false);
        $response->assertDontSee(route('admin.dashboard'), false);
    }

    public function test_login_validation_messages_are_vietnamese(): void
    {
        $response = $this->from(route('login'))->post(route('login'), []);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Vui lòng nhập email.',
            'password' => 'Vui lòng nhập mật khẩu.',
        ]);
    }
}
