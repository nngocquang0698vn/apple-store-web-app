<?php

namespace Tests\Feature;

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
        $response->assertSee('data-home-showcase', false);

        if (is_file(storage_path('app/public/products/demo/iphone-15-black.webp'))) {
            $response->assertSee('/storage/products/demo/', false);
            $response->assertSee('iPhone 16 Pro', false);
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
    }
}
