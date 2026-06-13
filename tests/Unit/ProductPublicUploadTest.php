<?php

namespace Tests\Unit;

use App\Support\ProductPublicUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductPublicUploadTest extends TestCase
{
    public function test_store_persists_file_and_returns_relative_path(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('iphone.webp');
        $path = ProductPublicUpload::store($file, 'products/3');

        $this->assertMatchesRegularExpression('#^products/3/.+\.webp$#', $path);
        Storage::disk('public')->assertExists($path);
    }
}
