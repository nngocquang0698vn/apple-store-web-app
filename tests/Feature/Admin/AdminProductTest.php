<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSeries;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_products(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $series = ProductSeries::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'product_series_id' => $series->id,
            'name' => 'iPhone 17 Pro',
            'slug' => '',
            'short_description' => 'Sản phẩm thử nghiệm.',
            'description' => 'Mô tả chi tiết.',
            'specifications' => 'A-series chip',
            'release_year' => '2026',
            'is_featured' => '1',
            'is_active' => '1',
        ]);

        $product = Product::query()->where('slug', 'iphone-17-pro')->first();

        $this->assertNotNull($product);
        $response->assertRedirect(route('admin.products.show', $product->id));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $category->id,
            'product_series_id' => $series->id,
            'is_featured' => true,
        ]);

        $this->actingAs($admin)->patch(route('admin.products.update', $product->id), [
            'category_id' => $category->id,
            'product_series_id' => $series->id,
            'name' => 'iPhone 17 Pro Max',
            'slug' => 'iphone-17-pro-max',
            'short_description' => null,
            'description' => null,
            'specifications' => null,
            'release_year' => '2026',
            'is_featured' => '0',
            'is_active' => '0',
        ])->assertRedirect(route('admin.products.show', $product->id));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'iPhone 17 Pro Max',
            'slug' => 'iphone-17-pro-max',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product->id))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_product_series_must_belong_to_selected_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $otherCategory = Category::factory()->create();
        $series = ProductSeries::factory()->create(['category_id' => $otherCategory->id]);

        $response = $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'product_series_id' => $series->id,
                'name' => 'iPhone lệch danh mục',
                'slug' => '',
                'is_featured' => '0',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.products.create'));
        $response->assertSessionHasErrors('product_series_id');
    }

    public function test_admin_can_upload_product_image_and_set_primary(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone.png'),
            'alt_text' => 'Ảnh iPhone',
            'sort_order' => '1',
            'is_primary' => '1',
        ]);

        $image = ProductImage::query()->where('product_id', $product->id)->first();

        $response->assertRedirect(route('admin.products.show', $product->id));
        $this->assertNotNull($image);
        $this->assertTrue($image->is_primary);
        Storage::disk('public')->assertExists($image->path);

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-2.png'),
            'alt_text' => 'Ảnh chính mới',
            'sort_order' => '0',
            'is_primary' => '1',
        ])->assertRedirect(route('admin.products.show', $product->id));

        $this->assertFalse($image->fresh()->is_primary);
        $this->assertTrue(ProductImage::query()->where('alt_text', 'Ảnh chính mới')->first()->is_primary);
    }

    public function test_first_product_image_is_auto_primary(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone.png'),
        ])->assertRedirect(route('admin.products.show', $product->id));

        $image = ProductImage::query()->where('product_id', $product->id)->first();

        $this->assertNotNull($image);
        $this->assertTrue($image->is_primary);
    }

    public function test_admin_can_upload_product_image_via_json(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('admin.products.images.store', $product->id), [
                'image' => $this->fakePng('iphone.png'),
                'alt_text' => 'Ảnh JSON',
            ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.images.0.alt_text', 'Ảnh JSON');

        $image = ProductImage::query()->where('product_id', $product->id)->first();
        $this->assertNotNull($image);
        $this->assertStringStartsWith('products/'.$product->id.'/', $image->path);
    }

    public function test_admin_can_set_primary_image_via_json(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-1.png'),
            'is_primary' => '1',
        ]);

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-2.png'),
            'is_primary' => '0',
        ]);

        $secondary = ProductImage::query()
            ->where('product_id', $product->id)
            ->where('is_primary', false)
            ->first();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(route('admin.product-images.primary', $secondary));

        $response->assertOk();
        $this->assertTrue($secondary->fresh()->is_primary);

        $payloadImage = collect($response->json('data.images'))->firstWhere('id', $secondary->id);
        $this->assertNotNull($payloadImage);
        $this->assertTrue($payloadImage['is_primary']);
    }

    public function test_admin_can_reorder_images_via_json(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-1.png'),
            'sort_order' => '1',
        ]);

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-2.png'),
            'sort_order' => '2',
        ]);

        $second = ProductImage::query()->where('sort_order', 2)->first();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(route('admin.product-images.move', $second), [
                'direction' => 'up',
            ]);

        $response->assertOk();
        $response->assertJsonPath('data.moved_image_id', $second->id);
        $this->assertSame(1, $second->fresh()->sort_order);
        $this->assertSame($second->id, $response->json('data.images.0.id'));
    }

    public function test_admin_can_reorder_images_when_sort_orders_are_duplicated(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-1.png'),
            'sort_order' => '0',
            'is_primary' => '1',
        ]);

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-2.png'),
            'sort_order' => '0',
            'is_primary' => '0',
        ]);

        $second = ProductImage::query()->orderByDesc('id')->first();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(route('admin.product-images.move', $second), [
                'direction' => 'up',
            ]);

        $response->assertOk();
        $this->assertSame(1, $second->fresh()->sort_order);
        $this->assertSame($second->id, $response->json('data.images.0.id'));
    }

    public function test_deleting_primary_image_promotes_next_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-1.png'),
            'sort_order' => '1',
            'is_primary' => '1',
        ]);

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone-2.png'),
            'sort_order' => '2',
            'is_primary' => '0',
        ]);

        $primary = ProductImage::query()->where('is_primary', true)->first();
        $secondary = ProductImage::query()->where('is_primary', false)->first();

        $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('admin.product-images.destroy', $primary))
            ->assertOk();

        $this->assertTrue($secondary->fresh()->is_primary);
    }

    public function test_admin_can_update_alt_text_via_json(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.images.store', $product->id), [
            'image' => $this->fakePng('iphone.png'),
        ]);

        $image = ProductImage::query()->first();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(route('admin.product-images.update', $image), [
                'alt_text' => 'iPhone màu xanh',
            ]);

        $response->assertOk();
        $this->assertSame('iPhone màu xanh', $image->fresh()->alt_text);
    }

    public function test_customer_cannot_upload_product_image(): void
    {
        Storage::fake('public');

        $customer = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($customer)
            ->post(route('admin.products.images.store', $product->id), [
                'image' => $this->fakePng('iphone.png'),
            ])
            ->assertRedirect(route('home'));

        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_admin_upload_rejects_invalid_product_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.products.show', $product->id))
            ->post(route('admin.products.images.store', $product->id), [
                'image' => UploadedFile::fake()->create('script.svg', 10, 'image/svg+xml'),
                'alt_text' => 'Không hợp lệ',
                'sort_order' => '0',
                'is_primary' => '0',
            ]);

        $response->assertRedirect(route('admin.products.show', $product->id));
        $response->assertSessionHasErrors('image');
        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_admin_upload_rejects_php_file_disguised_as_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('admin.products.images.store', $product->id), [
                'image' => UploadedFile::fake()->create('malware.php', 10, 'application/x-php'),
            ]);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_admin_product_show_includes_image_upload_ui(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.products.show', $product->id));

        $response->assertOk();
        $response->assertSee('data-image-upload', false);
        $response->assertSee('Chọn ảnh sản phẩm', false);
        $response->assertSee('Hình ảnh sản phẩm', false);
    }

    private function fakePng(string $name): File
    {
        $content = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=',
            true,
        );

        return UploadedFile::fake()->createWithContent($name, $content);
    }
}
