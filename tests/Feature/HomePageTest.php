<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Cửa hàng điện thoại Apple cho đồ án web', false);
        $response->assertSee('images/placeholders/product-placeholder.svg', false);
    }
}
