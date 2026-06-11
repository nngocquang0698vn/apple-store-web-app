<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSeries;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_create_and_edit_pages_include_rich_text_editor(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('data-rich-text-editor', false)
            ->assertSee('Mô tả chi tiết', false);

        $product = Product::factory()->create([
            'description' => '<h2>Mô tả cũ</h2><p>Nội dung cũ.</p>',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product->id))
            ->assertOk()
            ->assertSee('data-rich-text-editor', false)
            ->assertSee('Mô tả cũ', false)
            ->assertSee('Nội dung cũ.', false);
    }

    public function test_admin_can_save_formatted_description(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $series = ProductSeries::factory()->create(['category_id' => $category->id]);

        $description = '<h2>Tính năng</h2><ul><li>Chip A18</li><li>Camera 48MP</li></ul>';

        $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'product_series_id' => $series->id,
            'name' => 'iPhone Rich Text',
            'slug' => 'iphone-rich-text',
            'description' => $description,
            'is_featured' => '0',
            'is_active' => '1',
        ])->assertRedirect();

        $product = Product::query()->where('slug', 'iphone-rich-text')->first();

        $this->assertNotNull($product);
        $this->assertStringContainsString('<h2>Tính năng</h2>', $product->description);
        $this->assertStringContainsString('<li>Chip A18</li>', $product->description);
    }

    public function test_admin_save_strips_dangerous_description_html(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'iPhone XSS',
            'slug' => 'iphone-xss',
            'description' => '<p onclick="alert(1)">OK</p><script>alert(1)</script><iframe src="https://evil.test"></iframe>',
            'is_featured' => '0',
            'is_active' => '1',
        ])->assertRedirect();

        $product = Product::query()->where('slug', 'iphone-xss')->first();

        $this->assertNotNull($product);
        $this->assertStringNotContainsString('<script', $product->description);
        $this->assertStringNotContainsString('onclick', $product->description);
        $this->assertStringNotContainsString('<iframe', $product->description);
        $this->assertStringContainsString('OK', $product->description);
    }

    public function test_customer_sees_formatted_description_without_admin_editor(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'iphone-demo-rich',
            'description' => '<h2>Điểm nổi bật</h2><p><strong>Pin</strong> trâu cả ngày.</p>',
        ]);

        ProductVariant::factory()->create(['product_id' => $product->id]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('product-description', false);
        $response->assertSee('<h2>Điểm nổi bật</h2>', false);
        $response->assertSee('<strong>Pin</strong>', false);
        $response->assertDontSee('data-rich-text-editor', false);
        $response->assertDontSee('product-editor', false);
    }

    public function test_customer_cannot_access_admin_product_editor_page(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('admin.products.create'))
            ->assertRedirect(route('home'));
    }

    public function test_admin_can_upload_description_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson(route('admin.products.description-images.store'), [
            'image' => UploadedFile::fake()->image('description.jpg'),
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['url']);
        $this->assertStringStartsWith('/storage/products/description/', $response->json('url'));
    }

    public function test_admin_can_save_description_with_image_table_and_youtube(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $description = '<h2>Thông số</h2><table><tbody><tr><td>RAM</td><td>12GB</td></tr></tbody></table>';
        $description .= '<p><img src="/storage/products/description/demo.webp" alt="Ảnh"></p>';
        $description .= '<p><a href="https://www.youtube.com/watch?v=aqz-KE-bpKQ">Xem video</a></p>';

        $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Demo Rich Content',
            'slug' => 'demo-rich-content',
            'description' => $description,
            'is_featured' => '0',
            'is_active' => '1',
        ])->assertRedirect();

        $product = Product::query()->where('slug', 'demo-rich-content')->first();

        $this->assertNotNull($product);
        $this->assertStringContainsString('<table>', $product->description);
        $this->assertStringContainsString('/storage/products/description/demo.webp', $product->description);
        $this->assertStringContainsString('youtube.com/embed/aqz-KE-bpKQ', $product->description);
    }

    public function test_customer_product_page_renders_rich_description_without_overflow_classes(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'rich-content-product',
            'description' => '<h2>Tiêu đề</h2><table><tbody><tr><td>ChipsetMediaTekDimensity</td><td>12GB</td></tr></tbody></table>',
        ]);

        ProductVariant::factory()->create(['product_id' => $product->id]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('product-description', false)
            ->assertSee('<table>', false)
            ->assertSee('ChipsetMediaTekDimensity', false);
    }
}
