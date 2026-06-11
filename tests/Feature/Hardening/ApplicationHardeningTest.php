<?php

namespace Tests\Feature\Hardening;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_product_placeholder_asset_exists(): void
    {
        $this->assertFileExists(public_path('images/placeholders/product-placeholder.svg'));
    }

    public function test_storefront_layout_includes_accessibility_skip_link(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('id="main-content"', false);
        $response->assertSee('Chuyển tới nội dung chính', false);
    }

    public function test_admin_layout_includes_accessibility_skip_link(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('href="#admin-main-content"', false);
        $response->assertSee('id="admin-main-content"', false);
    }

    public function test_storefront_shows_category_quick_links(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('aria-label="Danh mục sản phẩm"', false);
        $response->assertSee(route('products.index', ['category' => 'iphone']), false);
        $response->assertSee(route('products.index', ['category' => 'ipad']), false);
        $response->assertSee('iPhone', false);
        $response->assertSee('iPad', false);
    }

    public function test_storefront_product_views_do_not_embed_external_image_urls(): void
    {
        $paths = [
            resource_path('views/products'),
            resource_path('views/components/product-card.blade.php'),
            resource_path('views/components/product-image.blade.php'),
            resource_path('views/components/home-product-showcase.blade.php'),
            resource_path('views/components/product-gallery.blade.php'),
            resource_path('views/cart'),
        ];

        foreach ($paths as $path) {
            if (is_file($path)) {
                $this->assertNoExternalImageHotlinksInFile($path);

                continue;
            }

            foreach (glob($path.'/*.blade.php') ?: [] as $file) {
                $this->assertNoExternalImageHotlinksInFile($file);
            }
        }
    }

    private function assertNoExternalImageHotlinksInFile(string $file): void
    {
        $content = (string) file_get_contents($file);

        $this->assertDoesNotMatchRegularExpression(
            '/src=["\']https?:\/\//',
            $content,
            "External image hotlink found in {$file}",
        );
    }
}
