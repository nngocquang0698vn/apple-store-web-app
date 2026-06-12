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

        $this->assertSame(12, ProductImage::query()->count());
        $this->assertSame(12, ProductImage::query()->where('is_primary', true)->count());

        $iphone16 = Product::query()->where('slug', 'iphone-16')->firstOrFail();
        $image = ProductImage::query()
            ->where('product_id', $iphone16->id)
            ->where('is_primary', true)
            ->first();

        $this->assertNotNull($image);
        $this->assertSame('products/demo/iphone-16-black.webp', $image->path);
        $this->assertSame('iPhone 16 màu đen', $image->alt_text);
    }
}
