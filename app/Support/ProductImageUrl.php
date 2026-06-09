<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class ProductImageUrl
{
    public static function resolve(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
