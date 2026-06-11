<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\ProductSeries;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategorySeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_categories(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('admin.categories.index'));

        $response->assertRedirect(route('home'));
    }

    public function test_admin_can_manage_categories(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Apple Watch',
            'slug' => '',
            'description' => 'Thiết bị đeo.',
            'is_active' => '1',
            'sort_order' => '5',
        ]);

        $category = Category::query()->where('slug', 'apple-watch')->first();

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertNotNull($category);
        $this->assertDatabaseHas('categories', [
            'name' => 'Apple Watch',
            'slug' => 'apple-watch',
            'sort_order' => 5,
        ]);

        $this->actingAs($admin)->patch(route('admin.categories.update', $category), [
            'name' => 'Apple Watch mới',
            'slug' => 'apple-watch-moi',
            'description' => null,
            'is_active' => '0',
            'sort_order' => '9',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Apple Watch mới',
            'slug' => 'apple-watch-moi',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_slug_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->create(['slug' => 'iphone']);

        $response = $this->actingAs($admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), [
                'name' => 'iPhone',
                'slug' => 'iphone',
                'is_active' => '1',
                'sort_order' => '0',
            ]);

        $response->assertRedirect(route('admin.categories.create'));
        $response->assertSessionHasErrors('slug');
    }

    public function test_admin_can_manage_product_series(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'iPhone']);

        $response = $this->actingAs($admin)->post(route('admin.product-series.store'), [
            'category_id' => $category->id,
            'name' => 'iPhone 16 Series',
            'slug' => '',
            'release_year' => '2024',
            'is_active' => '1',
            'sort_order' => '10',
        ]);

        $series = ProductSeries::query()->where('slug', 'iphone-16-series')->first();

        $response->assertRedirect(route('admin.product-series.index'));
        $this->assertNotNull($series);
        $this->assertDatabaseHas('product_series', [
            'category_id' => $category->id,
            'name' => 'iPhone 16 Series',
            'release_year' => 2024,
        ]);

        $this->actingAs($admin)->patch(route('admin.product-series.update', $series), [
            'category_id' => $category->id,
            'name' => 'iPhone 16',
            'slug' => 'iphone-16',
            'release_year' => '2024',
            'is_active' => '0',
            'sort_order' => '11',
        ])->assertRedirect(route('admin.product-series.index'));

        $this->assertDatabaseHas('product_series', [
            'id' => $series->id,
            'name' => 'iPhone 16',
            'slug' => 'iphone-16',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.product-series.destroy', $series))
            ->assertRedirect(route('admin.product-series.index'));

        $this->assertDatabaseMissing('product_series', ['id' => $series->id]);
    }
}
