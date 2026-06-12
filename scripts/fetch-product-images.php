<?php

/**
 * Tải ảnh demo từ Cellphones (best-effort).
 *
 * Cellphones có thể hết hàng, đổi URL hoặc không có đủ màu — script bỏ qua và giữ ảnh cũ.
 * Seeder fallback về file .webp sẵn có trong storage/app/public/products/demo/.
 *
 * Usage:
 *   php scripts/fetch-product-images.php
 *   php scripts/fetch-product-images.php iphone-16 airpods-max
 *   php scripts/fetch-product-images.php -v          # log chi tiết khi bỏ qua
 */

declare(strict_types=1);

const OUT_DIR = __DIR__.'/../storage/app/public/products/demo';
const BASE = 'https://cellphones.com.vn';

/** @var array<string, array{url: string, primary_color?: string, colors?: array<string, string|list<string>>, gallery?: int}> */
const PRODUCTS = [
    'iphone-15' => [
        'url' => BASE.'/iphone-15.html',
        'primary_color' => 'black',
        'colors' => ['black' => 'Đen', 'pink' => 'Hồng', 'blue' => 'Xanh dương'],
        'gallery' => 3,
    ],
    'iphone-15-pro' => [
        'url' => BASE.'/iphone-15-pro.html',
        'primary_color' => 'natural-titanium',
        'colors' => [
            'natural-titanium' => ['Titanium Tự Nhiên', 'Tự nhiên'],
            'black-titanium' => ['Titan Đen', 'Đen Titanium'],
            'white-titanium' => ['Titan Trắng', 'Trắng Titanium'],
        ],
        'gallery' => 3,
    ],
    'iphone-16' => [
        'url' => BASE.'/iphone-16.html',
        'primary_color' => 'black',
        'colors' => ['black' => 'Đen', 'pink' => 'Hồng', 'blue' => 'Xanh Lưu Ly'],
        'gallery' => 3,
    ],
    'iphone-16-pro' => [
        'url' => BASE.'/iphone-16-pro.html',
        'primary_color' => 'black-titanium',
        'colors' => [
            'black-titanium' => ['Titan Đen', 'Đen Titanium'],
            'natural-titanium' => ['Titan Tự Nhiên', 'Tự nhiên'],
            'white-titanium' => ['Titan Trắng', 'Trắng Titanium'],
        ],
        'gallery' => 3,
    ],
    'iphone-16-pro-max' => [
        'url' => BASE.'/iphone-16-pro-max.html',
        'primary_color' => 'black-titanium',
        'colors' => [
            'black-titanium' => ['Titan Đen', 'Đen Titanium'],
            'natural-titanium' => ['Titan Tự Nhiên', 'Tự nhiên'],
            'desert-titanium' => ['Titan Sa Mạc', 'Sa Mạc'],
        ],
        'gallery' => 3,
    ],
    'ipad-10-9' => [
        'url' => BASE.'/ipad-10-9-inch-2022.html',
        'primary_color' => 'blue',
        'colors' => ['blue' => 'Xanh dương', 'pink' => 'Hồng', 'white' => ['Bạc', 'Trắng']],
        'gallery' => 2,
    ],
    'ipad-air-m2' => [
        'url' => BASE.'/ipad-air-6-m2-11-inch.html',
        'primary_color' => 'blue',
        'colors' => ['blue' => 'Xanh dương', 'purple' => 'Tím', 'white' => 'Trắng'],
        'gallery' => 3,
    ],
    'ipad-pro-11-m4' => [
        'url' => BASE.'/ipad-pro-m4-11-inch.html',
        'primary_color' => 'black',
        'colors' => ['black' => 'Đen', 'white' => ['Bạc', 'Trắng']],
        'gallery' => 3,
    ],
    'apple-20w-usb-c-adapter' => [
        'url' => BASE.'/cu-sac-nhanh-iphone-20w-pd-type-c.html',
        'gallery' => 2,
    ],
    'apple-30w-usb-c-adapter' => [
        'url' => BASE.'/cu-sac-nhanh-apple-type-c-my1w2zaa-30w.html',
        'gallery' => 2,
    ],
    'usb-c-to-lightning-1m' => [
        'url' => BASE.'/cap-type-c-to-lightning-apple-mm0a3fe-a-1m.html',
        'gallery' => 2,
    ],
    'usb-c-cable-1m' => [
        'url' => BASE.'/cap-type-c-to-type-c-apple-60w-1m.html',
        'gallery' => 2,
    ],
    'airpods-3' => [
        'url' => BASE.'/apple-airpods-3.html',
        'gallery' => 3,
    ],
    'airpods-pro-2' => [
        'url' => BASE.'/apple-airpods-pro-2-usb-c.html',
        'gallery' => 3,
    ],
    'airpods-max' => [
        'url' => BASE.'/apple-airpods-max.html',
        'primary_color' => 'black',
        'colors' => [
            'black' => 'Đen',
            'blue' => ['Xanh dương', 'Xanh lam'],
        ],
        'gallery' => 2,
    ],
];

const PRODUCT_NAMES = [
    'iphone-15' => 'iPhone 15',
    'iphone-15-pro' => 'iPhone 15 Pro',
    'iphone-16' => 'iPhone 16',
    'iphone-16-pro' => 'iPhone 16 Pro',
    'iphone-16-pro-max' => 'iPhone 16 Pro Max',
    'ipad-10-9' => 'iPad 10.9 inch',
    'ipad-air-m2' => 'iPad Air M2',
    'ipad-pro-11-m4' => 'iPad Pro 11 inch M4',
    'apple-20w-usb-c-adapter' => 'Apple 20W USB-C Power Adapter',
    'apple-30w-usb-c-adapter' => 'Apple 30W USB-C Power Adapter',
    'usb-c-to-lightning-1m' => 'Cáp USB-C sang Lightning 1m',
    'usb-c-cable-1m' => 'Cáp USB-C 1m',
    'airpods-3' => 'AirPods (thế hệ 3)',
    'airpods-pro-2' => 'AirPods Pro (thế hệ 2)',
    'airpods-max' => 'AirPods Max',
];

$verbose = in_array('-v', $argv, true);
$only = array_values(array_filter(array_slice($argv, 1), static fn (string $arg): bool => $arg !== '-v'));
$targets = $only === [] ? PRODUCTS : array_intersect_key(PRODUCTS, array_flip($only));

if ($targets === []) {
    fwrite(STDERR, "Không có slug hợp lệ. Slug: ".implode(', ', array_keys(PRODUCTS))."\n");
    exit(1);
}

$manifestPath = OUT_DIR.'/fetch-manifest.json';
/** @var array<string, list<array{file: string, alt: string, primary: bool}>> $existingManifest */
$existingManifest = is_file($manifestPath)
    ? (json_decode((string) file_get_contents($manifestPath), true) ?: [])
    : [];

if (! is_dir(OUT_DIR)) {
    mkdir(OUT_DIR, 0755, true);
}

if (! extension_loaded('gd')) {
    fwrite(STDERR, "Cần bật extension GD (webp) trong php.ini của Laragon.\n");
    exit(1);
}

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
        'timeout' => 30,
    ],
]);

$manifest = $existingManifest;
$downloaded = 0;
$skipped = 0;

foreach ($targets as $slug => $config) {
    echo "== {$slug} ==\n";
    $manifest[$slug] = [];

    $html = fetchHtml($config['url'], $context);
    if ($html === null) {
        skipNote($verbose, "  Bỏ qua (không mở được trang): {$config['url']}");
        restoreManifestSlug($manifest, $slug, $existingManifest);
        $skipped++;

        continue;
    }

    $variants = parseColorVariants($html);
    $primaryVariantUrl = $config['url'];
    $primaryColor = $config['primary_color'] ?? null;

    foreach ($config['colors'] ?? [] as $colorSlug => $titleNeedle) {
        $variant = findVariantByTitle($variants, $titleNeedle);
        if ($variant === null) {
            $label = is_array($titleNeedle) ? implode(' / ', $titleNeedle) : $titleNeedle;
            skipNote($verbose, "  Bỏ qua màu không có trên web: {$label}");
            continue;
        }

        $variantHtml = fetchHtml($variant['url'], $context);
        if ($variantHtml === null) {
            continue;
        }

        $imageUrl = extractVariantColorImageUrl($variantHtml, $variant['title'])
            ?? extractVariantColorImageUrl($html, $variant['title']);

        if ($imageUrl === null) {
            skipNote($verbose, "  Bỏ qua (không lấy được ảnh): {$variant['title']}");
            continue;
        }

        $file = "{$slug}-{$colorSlug}.webp";
        $isPrimary = $primaryColor !== null ? $colorSlug === $primaryColor : $colorSlug === array_key_first($config['colors']);

        if (saveWebpFromUrl($imageUrl, OUT_DIR.'/'.$file, $context)) {
            echo "  + {$file} ({$variant['title']})\n";
            $manifest[$slug][] = [
                'file' => $file,
                'alt' => colorAltText($slug, $colorSlug),
                'primary' => $isPrimary,
            ];
            $downloaded++;

            if ($isPrimary) {
                $primaryVariantUrl = $variant['url'];
            }
        }
    }

    if ($manifest[$slug] === [] && ($config['colors'] ?? []) !== []) {
        restoreManifestSlug($manifest, $slug, $existingManifest);
        if ($manifest[$slug] !== []) {
            skipNote($verbose, '  Giữ ảnh manifest cũ (không tải được màu mới)');
        }
    }

    if (($config['colors'] ?? []) === [] && $manifest[$slug] === []) {
        $fallback = extractOgImageUrl($html);
        if ($fallback !== null && saveWebpFromUrl($fallback, OUT_DIR."/{$slug}.webp", $context)) {
            echo "  + {$slug}.webp (og)\n";
            $manifest[$slug][] = [
                'file' => "{$slug}.webp",
                'alt' => productDisplayName($slug),
                'primary' => true,
            ];
            $downloaded++;
        } elseif ($manifest[$slug] === []) {
            restoreManifestSlug($manifest, $slug, $existingManifest);
        }
    }

    $galleryLimit = $config['gallery'] ?? 0;
    if ($galleryLimit > 0) {
        $galleryHtml = fetchHtml($primaryVariantUrl, $context) ?? $html;
        $galleryUrls = extractNumberedGalleryUrls($galleryHtml, $galleryLimit);

        if ($galleryUrls === []) {
            $galleryUrls = extractFallbackGalleryUrls($galleryHtml, $galleryLimit);
        }

        if ($galleryUrls === []) {
            $galleryUrls = extractAccessoryGalleryUrls($galleryHtml, $galleryLimit);
        }

        foreach ($galleryUrls as $index => $galleryUrl) {
            $file = "{$slug}-view-".($index + 1).'.webp';
            if (saveWebpFromUrl($galleryUrl, OUT_DIR.'/'.$file, $context)) {
                echo "  + {$file}\n";
                $manifest[$slug][] = [
                    'file' => $file,
                    'alt' => productDisplayName($slug).' góc chụp '.($index + 1),
                    'primary' => false,
                ];
                $downloaded++;
            }
        }
    }
}

file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
$totalManifestImages = array_sum(array_map('count', $manifest));
echo "Xong: {$downloaded} ảnh mới, {$totalManifestImages} ảnh trong manifest";
if ($skipped > 0) {
    echo ", {$skipped} sản phẩm bỏ qua (dùng ảnh cũ/fallback)";
}
echo PHP_EOL;
echo "Manifest: {$manifestPath}".PHP_EOL;

function skipNote(bool $verbose, string $message): void
{
    if ($verbose) {
        fwrite(STDERR, $message.PHP_EOL);
    }
}

/**
 * @param  array<string, list<array{file: string, alt: string, primary: bool}>>  $manifest
 * @param  array<string, list<array{file: string, alt: string, primary: bool}>>  $existing
 */
function restoreManifestSlug(array &$manifest, string $slug, array $existing): void
{
    if (isset($existing[$slug]) && $existing[$slug] !== []) {
        $manifest[$slug] = $existing[$slug];
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
            'url' => BASE.$match[1],
            'title' => html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5),
        ];
    }

    return $variants;
}

/**
 * @param  list<array{title: string, url: string}>  $variants
 * @return array{title: string, url: string}|null
 */
/**
 * @param  string|list<string>  $needle
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

function extractAccessoryGalleryUrls(string $html, int $limit): array
{
    preg_match_all(
        '#(?:https://(?:cdn\d*\.)?cellphones\.com\.vn)(?:/[^/"]*)*/media/catalog/product/[^"\s<>]+\.(?:png|jpe?g|webp)#i',
        $html,
        $matches,
    );

    $urls = [];
    foreach (array_unique($matches[0]) as $url) {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        if (str_contains($path, '/wysiwyg/') || str_contains($path, 'flash_sale')) {
            continue;
        }

        $basename = basename($path);
        if (preg_match('/-(?:den|trang|hong|xanh|blue|black)\./i', $basename)) {
            continue;
        }

        $urls[] = normalizeCatalogUrl($url);
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

function productDisplayName(string $slug): string
{
    return PRODUCT_NAMES[$slug] ?? str_replace('-', ' ', $slug);
}

function colorAltText(string $slug, string $colorSlug): string
{
    $colorLabels = [
        'black' => 'màu đen',
        'white' => 'màu trắng',
        'blue' => 'màu xanh dương',
        'pink' => 'màu hồng',
        'purple' => 'màu tím',
        'green' => 'màu xanh lá',
        'natural-titanium' => 'màu Titanium tự nhiên',
        'black-titanium' => 'màu Titanium đen',
        'white-titanium' => 'màu Titanium trắng',
        'desert-titanium' => 'màu Titanium sa mạc',
    ];

    $label = $colorLabels[$colorSlug] ?? $colorSlug;

    return productDisplayName($slug).' '.$label;
}
