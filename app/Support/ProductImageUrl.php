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

        if (! str_contains($path, '/')) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Đổi src dạng /storage/... sang URL đúng theo APP_URL (cần khi chạy trong subfolder /public).
     */
    public static function rewriteStorageSrc(string $src): string
    {
        $src = trim($src);

        if (preg_match('#^/storage/(.+)$#', $src, $matches) === 1) {
            return Storage::disk('public')->url($matches[1]);
        }

        $path = parse_url($src, PHP_URL_PATH);

        if (is_string($path) && preg_match('#^/storage/(.+)$#', $path, $matches) === 1) {
            return Storage::disk('public')->url($matches[1]);
        }

        return $src;
    }
}
