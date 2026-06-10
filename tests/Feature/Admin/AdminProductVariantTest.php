<?php

namespace Tests\Feature\Admin;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductVariantTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_deactivate_variant(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();
        $color = Color::factory()->create();
        $storage = StorageOption::factory()->create(['capacity_gb' => 256]);

        $response = $this->actingAs($admin)->post(route('admin.products.variants.store', $product->id), [
            'color_id' => $color->id,
            'storage_option_id' => $storage->id,
            'sku' => 'IP17-BLK-256',
            'original_price' => '34990000',
            'sale_price' => '32990000',
            'stock_quantity' => '12',
            'is_active' => '1',
        ]);

        $variant = ProductVariant::query()->where('sku', 'IP17-BLK-256')->first();

        $response->assertRedirect(route('admin.products.variants.index', $product->id));
        $this->assertNotNull($variant);
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'color_id' => $color->id,
            'storage_option_id' => $storage->id,
            'sale_price' => 32990000,
        ]);

        $this->actingAs($admin)->patch(route('admin.variants.update', $variant), [
            'color_id' => $color->id,
            'storage_option_id' => $storage->id,
            'sku' => 'IP17-BLK-256-A',
            'original_price' => '34990000',
            'sale_price' => '31990000',
            'stock_quantity' => '8',
            'is_active' => '1',
        ])->assertRedirect(route('admin.products.variants.index', $product->id));

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'IP17-BLK-256-A',
            'stock_quantity' => 8,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.variants.destroy', $variant))
            ->assertRedirect(route('admin.products.variants.index', $product->id));

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'is_active' => false,
        ]);
    }

    public function test_variant_rejects_duplicate_sku_and_combination(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();
        $color = Color::factory()->create();
        $storage = StorageOption::factory()->create(['capacity_gb' => 512]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'color_id' => $color->id,
            'storage_option_id' => $storage->id,
            'sku' => 'DUPLICATE-SKU',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.products.variants.index', $product->id))
            ->post(route('admin.products.variants.store', $product->id), [
                'color_id' => $color->id,
                'storage_option_id' => $storage->id,
                'sku' => 'DUPLICATE-SKU',
                'original_price' => '20000000',
                'sale_price' => '19000000',
                'stock_quantity' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.products.variants.index', $product->id));
        $response->assertSessionHasErrors(['sku', 'color_id']);
    }

    public function test_variant_rejects_sale_price_higher_than_original_price(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.products.variants.index', $product->id))
            ->post(route('admin.products.variants.store', $product->id), [
                'color_id' => null,
                'storage_option_id' => null,
                'sku' => 'BAD-PRICE',
                'original_price' => '10000000',
                'sale_price' => '11000000',
                'stock_quantity' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.products.variants.index', $product->id));
        $response->assertSessionHasErrors('sale_price');
    }
}
