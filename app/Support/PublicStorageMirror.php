<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Copy public-disk files into public/storage for environments without storage:link (e.g. XAMPP submission).
 */
class PublicStorageMirror
{
    public static function mirror(string $relativePath): void
    {
        if (! self::shouldMirror()) {
            return;
        }

        $relativePath = self::normalizePath($relativePath);
        $source = Storage::disk('public')->path($relativePath);

        if (! is_file($source)) {
            return;
        }

        try {
            $destination = public_path('storage/'.$relativePath);
            $directory = dirname($destination);

            if (! is_dir($directory)) {
                File::ensureDirectoryExists($directory);
            }

            copy($source, $destination);
        } catch (\Throwable) {
            // Best-effort for XAMPP; skip when public/storage is a symlink or unavailable in tests.
        }
    }

    public static function remove(string $relativePath): void
    {
        if (! self::shouldMirror()) {
            return;
        }

        $relativePath = self::normalizePath($relativePath);
        $destination = public_path('storage/'.$relativePath);

        if (is_file($destination)) {
            unlink($destination);
        }
    }

    public static function shouldMirror(): bool
    {
        $publicStorage = public_path('storage');

        if (! file_exists($publicStorage)) {
            return true;
        }

        return ! is_link($publicStorage);
    }

    private static function normalizePath(string $path): string
    {
        return str_replace('\\', '/', ltrim($path, '/'));
    }
}
