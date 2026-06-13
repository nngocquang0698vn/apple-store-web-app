<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class ProductPublicUpload
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');
        $filename = Str::ulid().'.'.$file->extension();
        $path = $directory.'/'.$filename;

        // putFileAs() can return false on bind-mounted volumes even when the file was written.
        Storage::disk('public')->putFileAs($directory, $file, $filename);

        if (! Storage::disk('public')->exists($path)) {
            throw new RuntimeException('Không thể lưu ảnh sản phẩm.');
        }

        return $path;
    }
}
