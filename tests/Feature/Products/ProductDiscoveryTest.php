<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use App\Queries\ProductQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_products_index_renders_successfully(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee('Sản phẩm', false);
        $response->assertSee('iPhone 16 Pro', false);
        $response->assertSee('product-placeholder.svg', false);
    }

    public function test_products_index_only_shows_active_products_with_active_variants(): void
    {
        $inactiveProduct = Product::factory()->inactive()->create([
            'name' => 'Sản phẩm bị ẩn',
            'slug' => 'san-pham-bi-an',
            'category_id' => Category::query()->first()->id,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $inactiveProduct->id,
            'sku' => 'HIDDEN-SKU-001',
            'color_id' => Color::query()->value('id'),
            'storage_option_id' => StorageOption::query()->value('id'),
        ]);

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertDontSee('Sản phẩm bị ẩn', false);
    }

    public function test_search_by_product_name(): void
    {
        $response = $this->get(route('products.index', ['q' => 'iPhone 16 Pro']));

        $response->assertOk();
        $response->assertSee('/products/iphone-16-pro', false);
        $response->assertDontSee('/products/ipad-air-m2', false);
    }

    public function test_search_by_sku(): void
    {
        $variant = ProductVariant::query()->where('sku', 'IP16P-BLK-128')->first();

        $this->assertNotNull($variant);

        $response = $this->get(route('products.index', ['q' => 'IP16P-BLK-128']));

        $response->assertOk();
        $response->assertSee('iPhone 16 Pro', false);
    }

    public function test_filter_by_category(): void
    {
        $response = $this->get(route('products.index', ['category' => 'ipad']));

        $response->assertOk();
        $response->assertSee('/products/ipad-air-m2', false);
        $response->assertDontSee('/products/iphone-16', false);
    }

    public function test_filter_by_series(): void
    {
        $response = $this->get(route('products.index', ['series' => 'iphone-16']));

        $response->assertOk();
        $response->assertSee('/products/iphone-16', false);
        $response->assertSee('/products/iphone-16-pro', false);
        $response->assertDontSee('/products/iphone-15', false);
    }

    public function test_filter_by_color(): void
    {
        $color = Color::query()->where('slug', 'pink')->first();

        $this->assertNotNull($color);

        $response = $this->get(route('products.index', ['colors' => ['pink']]));

        $response->assertOk();
        $response->assertSee('iPhone 16', false);
    }

    public function test_filter_by_storage(): void
    {
        $storage = StorageOption::query()->where('capacity_gb', 512)->first();

        $this->assertNotNull($storage);

        $response = $this->get(route('products.index', [
            'category' => 'iphone',
            'series' => 'iphone-16',
            'storages' => [512],
        ]));

        $response->assertOk();
        $response->assertSee('iPhone 16 Pro', false);
    }

    public function test_filter_by_price_range(): void
    {
        $response = $this->get(route('products.index', [
            'min_price' => 25_000_000,
            'max_price' => 30_000_000,
            'category' => 'iphone',
        ]));

        $response->assertOk();
        $response->assertSee('/products/iphone-16-pro', false);
        $response->assertDontSee('/products/apple-20w-usb-c-adapter', false);
    }

    public function test_filter_in_stock_only(): void
    {
        $product = Product::query()->where('slug', 'apple-20w-usb-c-adapter')->first();
        $this->assertNotNull($product);

        ProductVariant::query()
            ->where('product_id', $product->id)
            ->update(['stock_quantity' => 0]);

        $response = $this->get(route('products.index', [
            'category' => 'phu-kien-sac',
            'in_stock' => 1,
        ]));

        $response->assertOk();
        $response->assertDontSee('/products/apple-20w-usb-c-adapter', false);
    }

    public function test_filter_featured_only(): void
    {
        $response = $this->get(route('products.index', ['featured' => 1]));

        $response->assertOk();
        $response->assertSee('/products/iphone-16-pro', false);
        $response->assertDontSee('/products/ipod-touch-gen-7', false);
    }

    public function test_combined_filters(): void
    {
        $response = $this->get(route('products.index', [
            'category' => 'iphone',
            'series' => 'iphone-16',
            'colors' => ['black'],
            'storages' => [256],
            'sort' => 'price_asc',
        ]));

        $response->assertOk();
        $response->assertSee('iPhone 16', false);
        $response->assertSee('iPhone 16 Pro', false);
    }

    public function test_sort_by_price_ascending(): void
    {
        $response = $this->get(route('products.index', [
            'category' => 'phu-kien-sac',
            'sort' => 'price_asc',
        ]));

        $response->assertOk();

        $content = $response->getContent();
        $cablePos = strpos($content, '/products/usb-c-cable-1m');
        $chargerPos = strpos($content, '/products/apple-20w-usb-c-adapter');

        $this->assertNotFalse($cablePos);
        $this->assertNotFalse($chargerPos);
        $this->assertLessThan($chargerPos, $cablePos);
    }

    public function test_invalid_sort_falls_back_to_newest(): void
    {
        $query = app(ProductQuery::class);

        $products = $query->paginate([
            'sort' => 'invalid_sort',
        ]);

        $this->assertGreaterThan(0, $products->count());
    }

    public function test_pagination_preserves_query_string(): void
    {
        $response = $this->get(route('products.index', [
            'sort' => 'name_asc',
        ]));

        $response->assertOk();
        $response->assertSee('sort=name_asc', false);

        if ($response->getContent() !== false && str_contains($response->getContent(), 'page=2')) {
            $response->assertSee('sort=name_asc', false);
        }
    }

    public function test_empty_result_shows_empty_state(): void
    {
        $response = $this->get(route('products.index', ['q' => 'khong-ton-tai-xyz-123']));

        $response->assertOk();
        $response->assertSee('Không tìm thấy sản phẩm', false);
    }

    public function test_product_listing_avoids_n_plus_one_queries(): void
    {
        Model::preventLazyLoading();

        DB::enableQueryLog();

        $this->get(route('products.index', ['category' => 'iphone']))->assertOk();

        $queryCount = count(DB::getQueryLog());

        $this->assertLessThanOrEqual(20, $queryCount);
    }

    public function test_products_are_not_duplicated_when_multiple_variants_match(): void
    {
        $query = app(ProductQuery::class);

        $products = $query->paginate([
            'category' => 'iphone',
            'colors' => ['black', 'blue', 'pink'],
        ]);

        $ids = $products->pluck('id')->all();

        $this->assertSame(count($ids), count(array_unique($ids)));
    }
}
