<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\ProductImageOrdering;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageOrderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_normalize_assigns_unique_sequential_sort_orders(): void
    {
        $product = Product::factory()->create();

        $first = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 0,
        ]);
        $second = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 0,
        ]);
        $third = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 0,
        ]);

        app(ProductImageOrdering::class)->normalize($product);

        $this->assertSame(1, $first->fresh()->sort_order);
        $this->assertSame(2, $second->fresh()->sort_order);
        $this->assertSame(3, $third->fresh()->sort_order);
    }

    public function test_move_up_swaps_with_previous_image_when_sort_orders_were_duplicated(): void
    {
        $product = Product::factory()->create();

        $first = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 0,
        ]);
        $second = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 0,
        ]);

        $moved = app(ProductImageOrdering::class)->move($second, 'up');

        $this->assertTrue($moved);
        $this->assertSame(1, $second->fresh()->sort_order);
        $this->assertSame(2, $first->fresh()->sort_order);
    }

    public function test_move_down_swaps_with_next_image(): void
    {
        $product = Product::factory()->create();

        $first = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 1,
        ]);
        $second = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 2,
        ]);

        $moved = app(ProductImageOrdering::class)->move($first, 'down');

        $this->assertTrue($moved);
        $this->assertSame(2, $first->fresh()->sort_order);
        $this->assertSame(1, $second->fresh()->sort_order);
    }
}
