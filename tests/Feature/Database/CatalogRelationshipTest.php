<?php

namespace Tests\Feature\Database;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CatalogRelationshipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_product_relationships_load_without_lazy_loading_violations(): void
    {
        Model::preventLazyLoading();

        $products = Product::query()
            ->with([
                'category',
                'productSeries',
                'images',
                'variants.color',
                'variants.storageOption',
            ])
            ->limit(5)
            ->get();

        foreach ($products as $product) {
            $this->assertNotNull($product->category->name);
            $this->assertGreaterThan(0, $product->variants->count());

            foreach ($product->variants as $variant) {
                if ($variant->color_id !== null) {
                    $this->assertNotNull($variant->color?->name);
                }
            }
        }
    }

    public function test_order_eager_loading_avoids_n_plus_one(): void
    {
        DB::enableQueryLog();

        $orders = Order::query()
            ->with(['user', 'items.product', 'items.productVariant'])
            ->limit(5)
            ->get();

        foreach ($orders as $order) {
            $this->assertNotNull($order->user->name);
            $this->assertGreaterThan(0, $order->items->count());
        }

        $queryCount = count(DB::getQueryLog());

        $this->assertLessThanOrEqual(6, $queryCount);
    }
}
