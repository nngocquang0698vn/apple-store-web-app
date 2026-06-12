<?php

declare(strict_types=1);

function demoSkip(bool $verbose, string $message): void
{
    if ($verbose) {
        fwrite(STDERR, $message.PHP_EOL);
    }
}

function fetchHtml(string $url, $context): ?string
{
    $html = @file_get_contents($url, false, $context);

    return $html === false ? null : $html;
}

/**
 * @return list<array{title: string, url: string}>
 */
function parseColorVariants(string $html): array
{
    $variants = [];

    preg_match_all(
        '/href="(\/[^"]+\?product_id=\d+)"[^>]*title="([^"]+)"/u',
        $html,
        $matches,
        PREG_SET_ORDER,
    );

    foreach ($matches as $match) {
        $variants[] = [
            'url' => 'https://cellphones.com.vn'.$match[1],
            'title' => html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5),
        ];
    }

    return $variants;
}

/**
 * @param  list<array{title: string, url: string}>  $variants
 * @param  string|list<string>  $needle
 * @return array{title: string, url: string}|null
 */
function findVariantByTitle(array $variants, string|array $needle): ?array
{
    $needles = is_array($needle) ? $needle : [$needle];

    foreach ($needles as $candidate) {
        $needleNorm = normalizeColor($candidate);

        foreach ($variants as $variant) {
            $titleNorm = normalizeColor($variant['title']);
            if ($titleNorm === $needleNorm || str_contains($titleNorm, $needleNorm) || str_contains($needleNorm, $titleNorm)) {
                return $variant;
            }
        }
    }

    return null;
}

function normalizeColor(string $value): string
{
    $value = mb_strtolower(trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5)));
    $value = str_replace(['titanium', 'titan'], 'titan', $value);

    return preg_replace('/\s+/u', ' ', $value) ?? $value;
}

function extractVariantColorImageUrl(string $html, string $colorTitle): ?string
{
    $title = preg_quote($colorTitle, '/');
    $pattern = '/title="'.$title.'"[^>]*<img[^>]+src="([^"]+)"/u';
    if (! preg_match($pattern, $html, $match)) {
        $pattern = '/<img[^>]+src="([^"]+)"[^>]+alt="[^"]*'.preg_quote($colorTitle, '/').'"/u';
        if (! preg_match($pattern, $html, $match)) {
            return null;
        }
    }

    return normalizeCatalogUrl($match[1]);
}

function extractNumberedGalleryUrls(string $html, int $limit): array
{
    preg_match_all(
        '#(?:https://(?:cdn\d*\.)?cellphones\.com\.vn)(?:/[^/"]*)*/media/catalog/product/[^"\s<>]+?-(\d+)\.(?:png|jpe?g|webp)#i',
        $html,
        $matches,
        PREG_SET_ORDER,
    );

    $byNumber = [];
    foreach ($matches as $match) {
        $number = (int) $match[1];
        if ($number < 1) {
            continue;
        }
        $byNumber[$number] = normalizeCatalogUrl($match[0]);
    }

    ksort($byNumber);

    return array_values(array_slice($byNumber, 0, $limit));
}

function extractFallbackGalleryUrls(string $html, int $limit): array
{
    preg_match_all(
        '#(?:https://(?:cdn\d*\.)?cellphones\.com\.vn)(?:/[^/"]*)*/media/catalog/product/[^"\s<>]+\.(?:png|jpe?g|webp)#i',
        $html,
        $matches,
    );

    $urls = [];
    foreach (array_unique($matches[0]) as $url) {
        $basename = basename(parse_url($url, PHP_URL_PATH) ?? '');
        if (preg_match('/-\d+\.(png|jpe?g|webp)$/i', $basename)) {
            $urls[] = normalizeCatalogUrl($url);
        }
    }

    return array_slice($urls, 0, $limit);
}

function extractOgImageUrl(string $html): ?string
{
    if (! preg_match('/property="og:image"\s+content="([^"]+)"/', $html, $match)) {
        return null;
    }

    return normalizeCatalogUrl(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5));
}

function normalizeCatalogUrl(string $url): string
{
    $url = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5);

    if (preg_match('#plain/(https://cellphones\.com\.vn/[^"\s]+)#', $url, $match)) {
        return $match[1];
    }

    if (preg_match('#cellphones\.com\.vn/media/catalog/product/#', $url)) {
        return preg_replace('#https://cdn\d*\.cellphones\.com\.vn/[^/]+/#', 'https://cellphones.com.vn/', $url) ?? $url;
    }

    return $url;
}

function saveWebpFromUrl(string $url, string $destPath, $context): bool
{
    $binary = @file_get_contents($url, false, $context);
    if ($binary === false) {
        return false;
    }

    $image = @imagecreatefromstring($binary);
    if ($image === false) {
        return false;
    }

    $width = imagesx($image);
    $height = imagesy($image);
    $canvasSize = 800;
    $canvas = imagecreatetruecolor($canvasSize, $canvasSize);
    $white = imagecolorallocate($canvas, 249, 250, 251);
    imagefill($canvas, 0, 0, $white);

    $scale = min($canvasSize / $width, $canvasSize / $height) * 0.9;
    $newW = (int) ($width * $scale);
    $newH = (int) ($height * $scale);
    $dstX = (int) (($canvasSize - $newW) / 2);
    $dstY = (int) (($canvasSize - $newH) / 2);

    imagecopyresampled($canvas, $image, $dstX, $dstY, 0, 0, $newW, $newH, $width, $height);
    imagewebp($canvas, $destPath, 82);
    imagedestroy($image);
    imagedestroy($canvas);

    return true;
}

function colorAltText(string $productName, string $colorSlug): string
{
    $labels = [
        'cosmic-orange' => 'Cam vũ trụ',
        'deep-blue' => 'Xanh đậm',
        'silver' => 'Bạc',
        'black' => 'Đen',
        'white' => 'Trắng',
        'lavender' => 'Tím oải hương',
        'sky-blue' => 'Xanh da trời',
        'cloud-white' => 'Trắng mây',
        'space-black' => 'Đen không gian',
    ];

    return $productName.' '.($labels[$colorSlug] ?? $colorSlug);
}

function extractSpecificationsBlock(string $html): string
{
    $allowedKeys = [
        'Hệ điều hành',
        'Chipset',
        'Bộ nhớ trong',
        'Loại CPU',
        'GPU',
        'Kích thước màn hình',
        'Công nghệ màn hình',
        'Độ phân giải màn hình',
        'Tính năng màn hình',
        'Camera sau',
        'Camera trước',
        'Thẻ SIM',
        'Công nghệ NFC',
        'Pin',
        'Thời điểm ra mắt',
    ];

    $pairs = [];

    if (preg_match_all('/<td[^>]*>(.*?)<\/td>\s*<td[^>]*>(.*?)<\/td>/su', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $key = trim(strip_tags(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5)));
            $value = trim(strip_tags(html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5)));
            if ($key === '' || $value === '' || ! in_array($key, $allowedKeys, true)) {
                continue;
            }
            $pairs[$key] = $value;
        }
    }

    if ($pairs === []) {
        return "Nguồn: Cellphones (tham khảo demo).\n";
    }

    $lines = [];
    foreach ($allowedKeys as $key) {
        if (isset($pairs[$key])) {
            $lines[] = "{$key}: {$pairs[$key]}";
        }
    }

    return implode("\n", $lines);
}

/**
 * @return list<string>
 */
function extractHighlightBullets(string $html): array
{
    $bullets = [];

    if (preg_match('/Tính năng nổi bật(.*?)Video/su', $html, $section)) {
        preg_match_all('/<li[^>]*>(.*?)<\/li>/su', $section[1], $items);
        foreach ($items[1] ?? [] as $item) {
            $text = trim(strip_tags(html_entity_decode($item, ENT_QUOTES | ENT_HTML5)));
            if ($text !== '' && mb_strlen($text) > 20) {
                $bullets[] = $text;
            }
        }
    }

    return array_values(array_unique(array_slice($bullets, 0, 5)));
}

/**
 * @param  array<string, mixed>  $config
 * @param  list<string>  $highlights
 */
function buildDescriptionHtml(array $config, array $highlights): string
{
    $name = htmlspecialchars((string) $config['name'], ENT_QUOTES, 'UTF-8');
    $short = htmlspecialchars((string) $config['short_description'], ENT_QUOTES, 'UTF-8');

    $items = '';
    foreach ($highlights as $bullet) {
        $items .= '<li>'.htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8').'</li>';
    }

    if ($items === '') {
        $items = '<li>'.htmlspecialchars((string) $config['short_description'], ENT_QUOTES, 'UTF-8').'</li>';
    }

    return <<<HTML
<p><strong>{$name}</strong> — {$short}</p>
<p>Sản phẩm demo được chuẩn bị từ thông tin công khai trên Cellphones, phục vụ buổi trình bày đồ án iStore.</p>
<h3>Điểm nổi bật</h3>
<ul>
{$items}
</ul>
<p><em>Lưu ý: Đây là website học tập, không phải cửa hàng Apple chính thức.</em></p>
HTML;
}

/**
 * @param  array<string, mixed>  $config
 * @param  list<array{file: string, alt: string, primary: bool, color_label: ?string}>  $images
 * @return array<string, mixed>
 */
function buildProductMeta(array $config, array $images, string $specifications): array
{
    $storageSteps = [0 => 0, 256 => 2_000_000, 512 => 4_000_000, 1024 => 8_000_000];
    $variants = [];
    $base = (int) $config['variant_base_price'];
    $prefix = (string) $config['variant_prefix'];

    foreach ($config['colors'] as $colorSlug => $labels) {
        $colorLabel = is_array($labels) ? $labels[0] : $labels;
        $colorCode = strtoupper(substr(preg_replace('/[^a-z]/', '', $colorSlug) ?? $colorSlug, 0, 3));

        foreach ($config['storages_gb'] as $storageGb) {
            $extra = $storageSteps[$storageGb] ?? 0;
            $sale = $base + $extra;
            $original = $sale + 1_000_000;
            $variants[] = [
                'sku' => sprintf('%s-%s-%d', $prefix, $colorCode, $storageGb),
                'color_label' => $colorLabel,
                'color_slug' => $colorSlug,
                'storage_gb' => $storageGb,
                'storage_label' => $storageGb.' GB',
                'original_price' => $original,
                'sale_price' => $sale,
                'stock_quantity' => 12,
                'is_active' => true,
            ];
        }
    }

    return [
        'name' => $config['name'],
        'slug' => $config['slug'],
        'category' => $config['category'],
        'product_series' => $config['product_series'],
        'release_year' => $config['release_year'],
        'is_featured' => (bool) ($config['is_featured'] ?? false),
        'is_active' => true,
        'short_description' => $config['short_description'],
        'reference_price_vnd' => $config['reference_price_vnd'],
        'list_price_vnd' => $config['list_price_vnd'],
        'source_url' => $config['url'],
        'images' => $images,
        'suggested_variants' => $variants,
        'specifications_preview' => strtok($specifications, "\n") ?: '',
        'admin_notes' => [
            'Tạo dòng sản phẩm "iPhone 17 Series" (năm 2025) trước nếu chưa có.',
            'Thêm màu trong admin nếu chưa có (xem color_label trong suggested_variants).',
            'Upload ảnh từ thư mục images/ theo thứ tự manifest.json.',
            'Dán description.html vào trình soạn thảo mô tả chi tiết.',
            'Dán specifications.txt vào ô Thông số.',
        ],
    ];
}
