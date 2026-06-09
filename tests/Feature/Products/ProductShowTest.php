<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_product_show_renders_successfully(): void
    {
        $response = $this->get(route('products.show', 'iphone-16-pro'));

        $response->assertOk();
        $response->assertSee('iPhone 16 Pro', false);
        $response->assertSee('Thêm vào giỏ hàng', false);
        $response->assertSee('Màu sắc', false);
        $response->assertSee('Dung lượng', false);
    }

    public function test_inactive_product_returns_not_found(): void
    {
        $product = Product::factory()->inactive()->create([
            'slug' => 'san-pham-an',
            'category_id' => Category::query()->value('id'),
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $this->get(route('products.show', $product))->assertNotFound();
    }

    public function test_product_without_active_variants_returns_not_found(): void
    {
        $product = Product::factory()->create([
            'slug' => 'khong-co-bien-the',
            'category_id' => Category::query()->value('id'),
        ]);

        ProductVariant::factory()->inactive()->create([
            'product_id' => $product->id,
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $this->get(route('products.show', $product))->assertNotFound();
    }

    public function test_color_and_storage_query_params_select_variant(): void
    {
        $variant = ProductVariant::query()
            ->where('sku', 'IP16P-BLK-256')
            ->with(['product', 'color', 'storageOption'])
            ->first();

        $this->assertNotNull($variant);

        $response = $this->get(route('products.show', [
            'product' => $variant->product,
            'color' => $variant->color->slug,
            'storage' => $variant->storageOption->capacity_gb,
        ]));

        $response->assertOk();
        $response->assertSee('IP16P-BLK-256', false);
        $response->assertSee((string) number_format($variant->sale_price, 0, ',', '.'), false);
    }

    public function test_out_of_stock_variant_disables_add_to_cart_button(): void
    {
        $product = Product::query()->where('slug', 'iphone-16-pro')->firstOrFail();
        $variant = ProductVariant::query()
            ->where('product_id', $product->id)
            ->firstOrFail();

        $variant->update(['stock_quantity' => 0]);

        $response = $this->get(route('products.show', [
            'product' => $product,
            'color' => $variant->color->slug,
            'storage' => $variant->storageOption->capacity_gb,
        ]));

        $response->assertOk();
        $response->assertSee('Hết hàng', false);
        $response->assertDontSee('Thêm vào giỏ hàng', false);
    }
}
