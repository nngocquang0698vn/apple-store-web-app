<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
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

    public function test_product_show_renders_gallery_with_multiple_images(): void
    {
        $product = Product::query()->where('slug', 'iphone-16-pro')->firstOrFail();

        ProductImage::factory()->count(2)->sequence(
            ['sort_order' => 2, 'is_primary' => false, 'alt_text' => 'Ảnh phụ 1'],
            ['sort_order' => 3, 'is_primary' => false, 'alt_text' => 'Ảnh phụ 2'],
        )->create(['product_id' => $product->id]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('data-product-gallery', false);
        $response->assertSee('data-carousel-main-image', false);
        $response->assertSee('data-carousel-thumb', false);
        $response->assertSee('Ảnh trước', false);
        $response->assertSee('Ảnh tiếp theo', false);
    }

    public function test_product_show_renders_placeholder_when_product_has_no_images(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'san-pham-khong-anh',
            'description' => null,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('images/placeholders/product-placeholder.svg', false);
        $response->assertSee('Ảnh sản phẩm đang được cập nhật.', false);
        $response->assertSee('Thông tin chi tiết sản phẩm đang được cập nhật.', false);
        $response->assertDontSee('data-product-gallery', false);
    }

    public function test_product_show_hides_gallery_controls_for_single_image(): void
    {
        $product = Product::query()->where('slug', 'iphone-16-pro')->firstOrFail();

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('data-carousel-main-image', false);
        $response->assertDontSee('data-product-gallery', false);
        $response->assertDontSee('Ảnh trước', false);
    }

    public function test_product_show_parses_alternating_specification_lines(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'iphone-alt-specs',
            'specifications' => "Chipset\nMediaTek Dimensity\nRAM\n12 GB",
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('Chipset', false);
        $response->assertSee('MediaTek Dimensity', false);
        $response->assertSee('12 GB', false);
    }

    public function test_product_show_renders_description_and_specs_side_by_side_layout(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'iphone-side-by-side',
            'description' => '<p>Mô tả dài.</p>',
            'specifications' => "Màn hình: 6.1 inch\nPin: 4000 mAh",
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('data-product-detail-content', false);
        $response->assertSee('lg:col-span-7', false);
        $response->assertSee('lg:col-span-3', false);
        $response->assertSee('Mô tả chi tiết', false);
        $response->assertSee('Thông số kỹ thuật', false);
        $response->assertSee('Màn hình', false);
    }

    public function test_product_description_renders_sanitized_html(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'iphone-gallery-desc',
            'description' => '<h2>Điểm nổi bật</h2><p>Nội dung an toàn.</p><script>alert(1)</script>',
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('product-description', false);
        $response->assertSee('<h2>Điểm nổi bật</h2>', false);
        $response->assertSee('Nội dung an toàn.', false);
        $response->assertDontSee('alert(1)', false);
    }
}
