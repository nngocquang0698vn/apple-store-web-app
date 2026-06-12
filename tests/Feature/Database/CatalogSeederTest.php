<?php

namespace Tests\Feature\Database;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_catalog_seeder_creates_admin_account(): void
    {
        $admin = User::query()->where('email', 'admin@istore.test')->first();

        $this->assertNotNull($admin);
        $this->assertSame(UserRole::Admin, $admin->role);
    }

    public function test_catalog_seeder_creates_demo_catalog(): void
    {
        $this->assertGreaterThanOrEqual(3, Category::query()->count());
        $this->assertGreaterThanOrEqual(10, Product::query()->count());
        $this->assertGreaterThanOrEqual(8, Color::query()->count());
        $this->assertSame(5, StorageOption::query()->count());
        $this->assertGreaterThanOrEqual(30, ProductVariant::query()->count());
    }

    public function test_catalog_seeder_creates_orders_in_multiple_statuses(): void
    {
        $this->assertSame(10, Order::query()->count());
        $this->assertGreaterThanOrEqual(2, Order::query()->where('status', 'pending')->count());
        $this->assertGreaterThanOrEqual(2, Order::query()->where('status', 'completed')->count());
        $this->assertGreaterThanOrEqual(2, Order::query()->where('status', 'cancelled')->count());
    }

    public function test_catalog_seeder_includes_main_categories(): void
    {
        $slugs = Category::query()->pluck('slug')->all();

        $this->assertContains('iphone', $slugs);
        $this->assertContains('ipad', $slugs);
        $this->assertContains('phu-kien', $slugs);
        $this->assertNotContains('ipod', $slugs);
    }

    public function test_catalog_seeder_does_not_fail_when_demo_images_are_missing(): void
    {
        $this->assertGreaterThan(0, Product::query()->count());
    }

    public function test_catalog_seeder_creates_product_images_when_demo_files_exist(): void
    {
        if (! is_file(storage_path('app/public/products/demo/iphone-15-black.webp'))) {
            $this->markTestSkipped('Demo product images are not present in storage/app/public/products/demo.');
        }

        $this->assertSame(15, Product::query()->count());
        $this->assertGreaterThanOrEqual(15, ProductImage::query()->count());
        $this->assertSame(15, ProductImage::query()->where('is_primary', true)->count());
        $this->assertSame(15, Product::query()->whereHas('images')->count());

        $iphone16 = Product::query()->where('slug', 'iphone-16')->firstOrFail();
        $iphone16Images = ProductImage::query()->where('product_id', $iphone16->id)->get();
        $this->assertGreaterThanOrEqual(2, $iphone16Images->count());

        $primary = $iphone16Images->firstWhere('is_primary', true);
        $this->assertNotNull($primary);
        $this->assertStringContainsString('iphone-16', $primary->path);

        $airpodsPro = Product::query()->where('slug', 'airpods-pro-2')->firstOrFail();
        $this->assertNotNull($airpodsPro->specifications);
        $this->assertStringContainsString('Apple H2', $airpodsPro->specifications);
        $this->assertGreaterThanOrEqual(1, ProductImage::query()->where('product_id', $airpodsPro->id)->count());
    }
}
