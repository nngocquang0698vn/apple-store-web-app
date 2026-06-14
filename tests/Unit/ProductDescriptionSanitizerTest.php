<?php

namespace Tests\Unit;

use App\Support\ProductDescriptionSanitizer;
use App\Support\ProductDescriptionYoutube;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductDescriptionSanitizerTest extends TestCase
{
    public function test_sanitize_returns_null_for_empty_input(): void
    {
        $this->assertNull(ProductDescriptionSanitizer::sanitize(null));
        $this->assertNull(ProductDescriptionSanitizer::sanitize(''));
        $this->assertNull(ProductDescriptionSanitizer::sanitize('   '));
    }

    public function test_sanitize_wraps_plain_text_for_backward_compatibility(): void
    {
        $result = ProductDescriptionSanitizer::sanitize("Dòng 1\nDòng 2");

        $this->assertNotNull($result);
        $this->assertStringContainsString('<p>', $result);
        $this->assertStringContainsString('Dòng 1', $result);
        $this->assertStringContainsString('<br>', $result);
        $this->assertStringContainsString('Dòng 2', $result);
    }

    public function test_sanitize_keeps_allowed_headings_and_lists(): void
    {
        $input = '<h2>Tiêu đề</h2><ul><li>Một</li><li>Hai</li></ul><ol><li>Ba</li></ol>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertStringContainsString('<h2>Tiêu đề</h2>', $result);
        $this->assertStringContainsString('<ul>', $result);
        $this->assertStringContainsString('<ol>', $result);
        $this->assertStringContainsString('<li>Một</li>', $result);
    }

    public function test_prepare_normalizes_non_breaking_spaces_from_pasted_content(): void
    {
        $input = "<p>Thông\xC2\xA0số\xC2\xA0kỹ\xC2\xA0thuật</p>";

        $result = ProductDescriptionSanitizer::prepare($input);

        $this->assertNotNull($result);
        $this->assertStringContainsString('Thông số kỹ thuật', $result);
        $this->assertStringNotContainsString("\xC2\xA0", $result);
    }

    public function test_sanitize_keeps_tables_for_spec_content(): void
    {
        $input = '<table><thead><tr><th>Hạng mục</th><th>Giá trị</th></tr></thead><tbody><tr><td>Chip</td><td>A18 Pro</td></tr></tbody></table>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringContainsString('product-description-table-wrap', $result);
        $this->assertStringContainsString('<table>', $result);
        $this->assertStringContainsString('<th>Hạng mục</th>', $result);
        $this->assertStringContainsString('<td>A18 Pro</td>', $result);
    }

    public function test_sanitize_keeps_storage_images(): void
    {
        $input = '<p><img src="/storage/products/description/demo.webp" alt="Ảnh demo"></p>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $expectedUrl = Storage::disk('public')->url('products/description/demo.webp');
        $this->assertStringContainsString('src="'.$expectedUrl.'"', $result);
        $this->assertStringContainsString('alt="Ảnh demo"', $result);
    }

    public function test_sanitize_removes_external_images(): void
    {
        $input = '<p><img src="https://evil.test/image.jpg" alt="Nguy hiểm"></p><p>An toàn</p>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('evil.test', $result);
        $this->assertStringContainsString('An toàn', $result);
    }

    public function test_sanitize_keeps_youtube_embed_iframe(): void
    {
        $input = ProductDescriptionYoutube::embedHtml('aqz-KE-bpKQ', 'Video demo');

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringContainsString('class="video-embed"', $result);
        $this->assertStringContainsString('youtube.com/embed/aqz-KE-bpKQ', $result);
    }

    public function test_sanitize_removes_non_youtube_iframe(): void
    {
        $input = '<div class="video-embed"><iframe src="https://evil.test/embed/1"></iframe></div>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('<iframe', $result);
        $this->assertStringNotContainsString('evil.test', $result);
    }

    public function test_prepare_converts_youtube_links_to_embeds(): void
    {
        $input = '<p>Xem thêm <a href="https://www.youtube.com/watch?v=aqz-KE-bpKQ">video</a></p>';

        $result = ProductDescriptionSanitizer::prepare($input);

        $this->assertNotNull($result);
        $this->assertStringContainsString('youtube.com/embed/aqz-KE-bpKQ', $result);
        $this->assertStringNotContainsString('watch?v=', $result);
    }

    public function test_sanitize_removes_script_tags(): void
    {
        $input = '<p>An toàn</p><script>alert("xss")</script>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('<script', $result);
        $this->assertStringNotContainsString('alert', $result);
        $this->assertStringContainsString('An toàn', $result);
    }

    public function test_sanitize_removes_event_handlers(): void
    {
        $input = '<p onclick="alert(1)">Nội dung</p>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('onclick', $result);
        $this->assertStringContainsString('Nội dung', $result);
    }

    public function test_sanitize_removes_dangerous_links(): void
    {
        $input = '<p><a href="javascript:alert(1)">Nguy hiểm</a> <a href="https://apple.com">An toàn</a></p>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('javascript:', $result);
        $this->assertStringContainsString('href="https://apple.com"', $result);
        $this->assertStringContainsString('An toàn', $result);
    }

    public function test_sanitize_adds_rel_for_blank_target_links(): void
    {
        $input = '<p><a href="https://apple.com" target="_blank">Apple</a></p>';

        $result = ProductDescriptionSanitizer::sanitize($input);

        $this->assertNotNull($result);
        $this->assertStringContainsString('rel="noopener noreferrer"', $result);
    }
}
