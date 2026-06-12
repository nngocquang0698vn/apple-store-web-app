<?php

/**
 * Tải ảnh carousel + metadata demo từ Cellphones cho thư mục demo/products/.
 *
 * Usage:
 *   php scripts/fetch-demo-resources.php
 *   php scripts/fetch-demo-resources.php -v
 */

declare(strict_types=1);

const DEMO_BASE = __DIR__.'/../demo/products';
const WEB_BASE = 'https://cellphones.com.vn';

/** @var array<string, array<string, mixed>> */
const DEMO_PRODUCTS = [
    'iphone-17-pro' => [
        'url' => WEB_BASE.'/iphone-17-pro.html',
        'name' => 'iPhone 17 Pro',
        'slug' => 'iphone-17-pro',
        'category' => 'iPhone',
        'product_series' => 'iPhone 17 Series',
        'release_year' => 2025,
        'is_featured' => true,
        'short_description' => 'iPhone 17 Pro với chip A19 Pro, camera Pro Fusion 48MP, zoom quang 8x và khung nhôm rèn nhiệt.',
        'reference_price_vnd' => 33_790_000,
        'list_price_vnd' => 34_990_000,
        'primary_color' => 'cosmic-orange',
        'colors' => [
            'cosmic-orange' => ['Cam Vũ Trụ', 'Cam'],
            'deep-blue' => ['Xanh Đậm', 'Xanh đậm'],
            'silver' => ['Bạc', 'Bạc'],
        ],
        'gallery' => 4,
        'storages_gb' => [256, 512, 1024],
        'variant_prefix' => 'IP17P',
        'variant_base_price' => 33_790_000,
    ],
    'iphone-17' => [
        'url' => WEB_BASE.'/iphone-17-256gb.html',
        'name' => 'iPhone 17',
        'slug' => 'iphone-17',
        'category' => 'iPhone',
        'product_series' => 'iPhone 17 Series',
        'release_year' => 2025,
        'is_featured' => true,
        'short_description' => 'iPhone 17 với chip A19, màn hình ProMotion 6.3 inch, camera Dual Fusion 48MP và pin dùng cả ngày.',
        'reference_price_vnd' => 23_990_000,
        'list_price_vnd' => 24_990_000,
        'primary_color' => 'black',
        'colors' => [
            'black' => ['Đen', 'Đen'],
            'white' => ['Trắng', 'Trắng'],
            'lavender' => ['Tím Oải Hương', 'Tím'],
        ],
        'gallery' => 4,
        'storages_gb' => [256, 512],
        'variant_prefix' => 'IP17',
        'variant_base_price' => 23_990_000,
    ],
    'iphone-air' => [
        'url' => WEB_BASE.'/iphone-air-256gb.html',
        'name' => 'iPhone Air',
        'slug' => 'iphone-air',
        'category' => 'iPhone',
        'product_series' => 'iPhone 17 Series',
        'release_year' => 2025,
        'is_featured' => true,
        'short_description' => 'iPhone Air siêu mỏng 5,6 mm, khung titan, chip A19 Pro và màn hình Super Retina XDR 6,5 inch.',
        'reference_price_vnd' => 22_890_000,
        'list_price_vnd' => 31_990_000,
        'primary_color' => 'sky-blue',
        'colors' => [
            'sky-blue' => ['Xanh Da Trời', 'Xanh da trời'],
            'cloud-white' => ['Trắng Mây', 'Trắng mây', 'Mây'],
            'space-black' => ['Đen Không Gian', 'Đen'],
        ],
        'gallery' => 4,
        'storages_gb' => [256, 512],
        'variant_prefix' => 'IPAIR',
        'variant_base_price' => 22_890_000,
    ],
];

require __DIR__.'/demo-resource-helpers.php';

$verbose = in_array('-v', $argv, true);
$only = array_values(array_filter(array_slice($argv, 1), static fn (string $arg): bool => $arg !== '-v'));
$targets = $only === [] ? DEMO_PRODUCTS : array_intersect_key(DEMO_PRODUCTS, array_flip($only));

if ($targets === []) {
    fwrite(STDERR, "Slug hợp lệ: ".implode(', ', array_keys(DEMO_PRODUCTS))."\n");
    exit(1);
}

if (! extension_loaded('gd')) {
    fwrite(STDERR, "Cần bật extension GD (webp) trong php.ini.\n");
    exit(1);
}

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
        'timeout' => 45,
    ],
]);

foreach ($targets as $slug => $config) {
    echo "== {$slug} ==\n";
    $productDir = DEMO_BASE.'/'.$slug;
    $imageDir = $productDir.'/images';
    if (! is_dir($imageDir)) {
        mkdir($imageDir, 0755, true);
    }

    $html = fetchHtml($config['url'], $context);
    if ($html === null) {
        fwrite(STDERR, "  Không mở được trang: {$config['url']}\n");
        continue;
    }

    $manifest = [];
    $variants = parseColorVariants($html);
    $primaryVariantUrl = $config['url'];
    $primaryColor = $config['primary_color'];

    foreach ($config['colors'] as $colorSlug => $titleNeedle) {
        $variant = findVariantByTitle($variants, $titleNeedle);
        if ($variant === null) {
            demoSkip($verbose, '  Bỏ qua màu: '.(is_array($titleNeedle) ? implode('/', $titleNeedle) : $titleNeedle));
            continue;
        }

        $variantHtml = fetchHtml($variant['url'], $context) ?? $html;
        $imageUrl = extractVariantColorImageUrl($variantHtml, $variant['title'])
            ?? extractVariantColorImageUrl($html, $variant['title']);

        if ($imageUrl === null) {
            demoSkip($verbose, "  Không lấy được ảnh màu: {$variant['title']}");
            continue;
        }

        $file = "{$colorSlug}.webp";
        $isPrimary = $colorSlug === $primaryColor;

        if (saveWebpFromUrl($imageUrl, $imageDir.'/'.$file, $context)) {
            echo "  + images/{$file}\n";
            $manifest[] = [
                'file' => $file,
                'alt' => colorAltText($config['name'], $colorSlug),
                'primary' => $isPrimary,
                'color_label' => $variant['title'],
            ];

            if ($isPrimary) {
                $primaryVariantUrl = $variant['url'];
            }
        }
    }

    if ($manifest === []) {
        $fallback = extractOgImageUrl($html);
        if ($fallback !== null && saveWebpFromUrl($fallback, $imageDir.'/primary.webp', $context)) {
            echo "  + images/primary.webp (fallback)\n";
            $manifest[] = [
                'file' => 'primary.webp',
                'alt' => $config['name'],
                'primary' => true,
                'color_label' => null,
            ];
        }
    }

    $galleryLimit = (int) ($config['gallery'] ?? 0);
    if ($galleryLimit > 0) {
        $galleryHtml = fetchHtml($primaryVariantUrl, $context) ?? $html;
        $galleryUrls = extractNumberedGalleryUrls($galleryHtml, $galleryLimit);
        if ($galleryUrls === []) {
            $galleryUrls = extractFallbackGalleryUrls($galleryHtml, $galleryLimit);
        }

        foreach ($galleryUrls as $index => $galleryUrl) {
            $file = 'view-'.($index + 1).'.webp';
            if (saveWebpFromUrl($galleryUrl, $imageDir.'/'.$file, $context)) {
                echo "  + images/{$file}\n";
                $manifest[] = [
                    'file' => $file,
                    'alt' => $config['name'].' góc chụp '.($index + 1),
                    'primary' => false,
                    'color_label' => null,
                ];
            }
        }
    }

    $specifications = extractSpecificationsBlock($html);
    $highlights = extractHighlightBullets($html);

    file_put_contents($productDir.'/specifications.txt', $specifications);
    file_put_contents($productDir.'/description.html', buildDescriptionHtml($config, $highlights));
    file_put_contents($productDir.'/short_description.txt', (string) $config['short_description']);

    $productMeta = buildProductMeta($config, $manifest, $specifications);
    file_put_contents(
        $productDir.'/product.json',
        json_encode($productMeta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    );

    file_put_contents(
        $productDir.'/images/manifest.json',
        json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    );

    echo "  -> product.json, description.html, specifications.txt\n";
}

echo "Xong. Xem thư mục demo/products/\n";
