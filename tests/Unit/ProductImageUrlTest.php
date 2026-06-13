<?php

namespace Tests\Unit;

use App\Support\ProductImageUrl;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageUrlTest extends TestCase
{
    public function test_resolve_returns_null_for_empty_path(): void
    {
        $this->assertNull(ProductImageUrl::resolve(null));
        $this->assertNull(ProductImageUrl::resolve(''));
    }

    public function test_resolve_rejects_http_hotlink(): void
    {
        $this->assertNull(ProductImageUrl::resolve('http://cdn.example.com/iphone.jpg'));
    }

    public function test_resolve_rejects_https_hotlink(): void
    {
        $this->assertNull(ProductImageUrl::resolve('https://cdn.example.com/iphone.jpg'));
    }

    public function test_resolve_returns_storage_url_for_local_path(): void
    {
        Storage::fake('public');

        $url = ProductImageUrl::resolve('products/demo/iphone.webp');

        $this->assertNotNull($url);
        $this->assertStringContainsString('products/demo/iphone.webp', $url);
    }

    public function test_rewrite_storage_src_uses_app_url_prefix(): void
    {
        config([
            'app.url' => 'http://localhost/public',
            'filesystems.disks.public.url' => 'http://localhost/public/storage',
        ]);

        $url = ProductImageUrl::rewriteStorageSrc('/storage/products/demo/iphone.webp');

        $this->assertSame('http://localhost/public/storage/products/demo/iphone.webp', $url);
    }
}
