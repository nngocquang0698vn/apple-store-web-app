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
        $this->assertGreaterThanOrEqual(4, Category::query()->count());
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
        $this->assertContains('ipod', $slugs);
        $this->assertContains('phu-kien-sac', $slugs);
    }

    public function test_catalog_seeder_works_without_product_images(): void
    {
        $this->assertSame(0, ProductImage::query()->count());
        $this->assertGreaterThan(0, Product::query()->count());
    }
}
