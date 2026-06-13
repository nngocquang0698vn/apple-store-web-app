<?php

namespace Tests\Unit;

use App\Support\PublicStorageMirror;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicStorageMirrorTest extends TestCase
{
    protected function tearDown(): void
    {
        $mirrorRoot = public_path('storage/mirror-test');

        if (is_dir($mirrorRoot)) {
            File::deleteDirectory($mirrorRoot);
        }

        parent::tearDown();
    }

    public function test_mirror_copies_file_when_public_storage_is_not_symlink(): void
    {
        $relativePath = 'mirror-test/sample.txt';
        $sourceDir = storage_path('app/public/mirror-test');
        File::ensureDirectoryExists($sourceDir);
        file_put_contents(storage_path('app/public/'.$relativePath), 'demo');

        $publicStorage = public_path('storage');
        $wasLink = is_link($publicStorage);

        if ($wasLink) {
            $this->markTestSkipped('public/storage is a symlink; mirror is skipped in this environment.');
        }

        PublicStorageMirror::mirror($relativePath);

        $this->assertFileExists(public_path('storage/'.$relativePath));
        $this->assertSame('demo', file_get_contents(public_path('storage/'.$relativePath)));
    }

    public function test_remove_deletes_mirrored_file_when_public_storage_is_not_symlink(): void
    {
        $relativePath = 'mirror-test/remove-me.txt';
        $mirrorDir = public_path('storage/mirror-test');
        File::ensureDirectoryExists($mirrorDir);
        file_put_contents(public_path('storage/'.$relativePath), 'demo');

        if (is_link(public_path('storage'))) {
            $this->markTestSkipped('public/storage is a symlink; mirror is skipped in this environment.');
        }

        PublicStorageMirror::remove($relativePath);

        $this->assertFileDoesNotExist(public_path('storage/'.$relativePath));
    }
}
