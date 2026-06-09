<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductFilterAjaxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_ajax_products_index_returns_partial_html(): void
    {
        $response = $this->get(route('products.index', ['q' => 'iPhone 16 Pro']), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertOk();
        $response->assertSee('iPhone 16 Pro', false);
        $response->assertSee('data-product-count', false);
        $response->assertDontSee('<!DOCTYPE html>', false);
    }

    public function test_ajax_filter_by_category_returns_matching_products(): void
    {
        $response = $this->get(route('products.index', ['category' => 'iphone']), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertOk();
        $response->assertSee('iPhone 16 Pro', false);
        $response->assertDontSee('iPad Air M2', false);
    }
}
