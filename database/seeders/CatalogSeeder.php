<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSeries;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use App\Models\User;
use App\Support\ProductDescriptionYoutube;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CatalogSeeder extends Seeder
{
    /**
     * @var array<string, Category>
     */
    private array $categories = [];

    /**
     * @var array<string, ProductSeries>
     */
    private array $series = [];

    /**
     * @var array<string, Color>
     */
    private array $colors = [];

    /**
     * @var array<int, StorageOption>
     */
    private array $storages = [];

    /**
     * @var array<int, Product>
     */
    private array $products = [];

    /**
     * @var array<int, ProductVariant>
     */
    private array $variants = [];

    /**
     * @var array<int, User>
     */
    private array $customers = [];

    public function run(): void
    {
        $this->seedUsers();
        $this->seedCategories();
        $this->seedProductSeries();
        $this->seedColors();
        $this->seedStorageOptions();
        $this->seedProducts();
        $this->seedProductImages();
        $this->seedVariants();
        $this->seedOrders();
    }

    private function seedUsers(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@istore.test'],
            [
                'name' => 'Quản trị viên iStore',
                'phone' => '0900000001',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'status' => UserStatus::Active,
            ],
        );

        for ($i = 1; $i <= 5; $i++) {
            $this->customers[] = User::factory()->create([
                'name' => "Khách hàng {$i}",
                'email' => "customer{$i}@istore.test",
                'phone' => '09'.str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
            ]);
        }
    }

    private function seedCategories(): void
    {
        $definitions = [
            ['name' => 'iPhone', 'slug' => 'iphone', 'sort_order' => 1],
            ['name' => 'iPad', 'slug' => 'ipad', 'sort_order' => 2],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'sort_order' => 3],
        ];

        foreach ($definitions as $definition) {
            $this->categories[$definition['slug']] = Category::query()->create([
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'description' => "Danh mục {$definition['name']} dành cho đồ án iStore.",
                'is_active' => true,
                'sort_order' => $definition['sort_order'],
            ]);
        }
    }

    private function seedProductSeries(): void
    {
        $definitions = [
            ['category' => 'iphone', 'name' => 'iPhone 15 Series', 'slug' => 'iphone-15', 'release_year' => 2023],
            ['category' => 'iphone', 'name' => 'iPhone 16 Series', 'slug' => 'iphone-16', 'release_year' => 2024],
            ['category' => 'ipad', 'name' => 'iPad', 'slug' => 'ipad', 'release_year' => 2022],
            ['category' => 'ipad', 'name' => 'iPad Air', 'slug' => 'ipad-air', 'release_year' => 2024],
            ['category' => 'ipad', 'name' => 'iPad Pro', 'slug' => 'ipad-pro', 'release_year' => 2024],
            ['category' => 'phu-kien', 'name' => 'USB-C Chargers', 'slug' => 'usb-c-chargers', 'release_year' => 2023],
            ['category' => 'phu-kien', 'name' => 'Charging Cables', 'slug' => 'charging-cables', 'release_year' => 2023],
            ['category' => 'phu-kien', 'name' => 'AirPods', 'slug' => 'airpods', 'release_year' => 2024],
        ];

        foreach ($definitions as $index => $definition) {
            $this->series[$definition['slug']] = ProductSeries::query()->create([
                'category_id' => $this->categories[$definition['category']]->id,
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'release_year' => $definition['release_year'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedColors(): void
    {
        $definitions = [
            ['name' => 'Đen', 'slug' => 'black', 'hex_code' => '#1F2937'],
            ['name' => 'Trắng', 'slug' => 'white', 'hex_code' => '#F9FAFB'],
            ['name' => 'Xanh dương', 'slug' => 'blue', 'hex_code' => '#2563EB'],
            ['name' => 'Hồng', 'slug' => 'pink', 'hex_code' => '#EC4899'],
            ['name' => 'Tím', 'slug' => 'purple', 'hex_code' => '#7C3AED'],
            ['name' => 'Xanh lá', 'slug' => 'green', 'hex_code' => '#16A34A'],
            ['name' => 'Titanium tự nhiên', 'slug' => 'natural-titanium', 'hex_code' => '#9CA3AF'],
            ['name' => 'Titanium đen', 'slug' => 'black-titanium', 'hex_code' => '#374151'],
            ['name' => 'Titanium trắng', 'slug' => 'white-titanium', 'hex_code' => '#E5E7EB'],
            ['name' => 'Titanium xanh sa mạc', 'slug' => 'desert-titanium', 'hex_code' => '#D6BFA3'],
        ];

        foreach ($definitions as $index => $definition) {
            $this->colors[$definition['slug']] = Color::query()->create([
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'hex_code' => $definition['hex_code'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedStorageOptions(): void
    {
        $definitions = [
            ['label' => '64 GB', 'capacity_gb' => 64],
            ['label' => '128 GB', 'capacity_gb' => 128],
            ['label' => '256 GB', 'capacity_gb' => 256],
            ['label' => '512 GB', 'capacity_gb' => 512],
            ['label' => '1 TB', 'capacity_gb' => 1024],
        ];

        foreach ($definitions as $index => $definition) {
            $this->storages[$definition['capacity_gb']] = StorageOption::query()->create([
                'label' => $definition['label'],
                'capacity_gb' => $definition['capacity_gb'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedProducts(): void
    {
        $definitions = [
            ['name' => 'iPhone 15', 'slug' => 'iphone-15', 'series' => 'iphone-15', 'category' => 'iphone', 'featured' => true, 'year' => 2023],
            ['name' => 'iPhone 15 Pro', 'slug' => 'iphone-15-pro', 'series' => 'iphone-15', 'category' => 'iphone', 'featured' => true, 'year' => 2023],
            ['name' => 'iPhone 16', 'slug' => 'iphone-16', 'series' => 'iphone-16', 'category' => 'iphone', 'featured' => true, 'year' => 2024],
            ['name' => 'iPhone 16 Pro', 'slug' => 'iphone-16-pro', 'series' => 'iphone-16', 'category' => 'iphone', 'featured' => true, 'year' => 2024],
            ['name' => 'iPhone 16 Pro Max', 'slug' => 'iphone-16-pro-max', 'series' => 'iphone-16', 'category' => 'iphone', 'featured' => false, 'year' => 2024],
            ['name' => 'iPad 10.9 inch', 'slug' => 'ipad-10-9', 'series' => 'ipad', 'category' => 'ipad', 'featured' => false, 'year' => 2022],
            ['name' => 'iPad Air M2', 'slug' => 'ipad-air-m2', 'series' => 'ipad-air', 'category' => 'ipad', 'featured' => true, 'year' => 2024],
            ['name' => 'iPad Pro 11 inch M4', 'slug' => 'ipad-pro-11-m4', 'series' => 'ipad-pro', 'category' => 'ipad', 'featured' => true, 'year' => 2024],
            ['name' => 'Apple 20W USB-C Power Adapter', 'slug' => 'apple-20w-usb-c-adapter', 'series' => 'usb-c-chargers', 'category' => 'phu-kien', 'featured' => true, 'year' => 2023],
            ['name' => 'Apple 30W USB-C Power Adapter', 'slug' => 'apple-30w-usb-c-adapter', 'series' => 'usb-c-chargers', 'category' => 'phu-kien', 'featured' => false, 'year' => 2023],
            ['name' => 'Cáp USB-C sang Lightning 1m', 'slug' => 'usb-c-to-lightning-1m', 'series' => 'charging-cables', 'category' => 'phu-kien', 'featured' => false, 'year' => 2023],
            ['name' => 'Cáp USB-C 1m', 'slug' => 'usb-c-cable-1m', 'series' => 'charging-cables', 'category' => 'phu-kien', 'featured' => false, 'year' => 2023],
            ['name' => 'AirPods (thế hệ 3)', 'slug' => 'airpods-3', 'series' => 'airpods', 'category' => 'phu-kien', 'featured' => false, 'year' => 2021],
            ['name' => 'AirPods Pro (thế hệ 2)', 'slug' => 'airpods-pro-2', 'series' => 'airpods', 'category' => 'phu-kien', 'featured' => true, 'year' => 2022],
            ['name' => 'AirPods Max', 'slug' => 'airpods-max', 'series' => 'airpods', 'category' => 'phu-kien', 'featured' => false, 'year' => 2020],
        ];

        foreach ($definitions as $index => $definition) {
            $this->products[] = Product::query()->create([
                'category_id' => $this->categories[$definition['category']]->id,
                'product_series_id' => $this->series[$definition['series']]->id,
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'short_description' => "{$definition['name']} chính hãng, bảo hành theo chính sách cửa hàng.",
                'description' => $this->productDescriptionHtml($definition['name'], $definition['slug']),
                'specifications' => $this->productSpecifications($definition['slug']),
                'release_year' => $definition['year'],
                'is_featured' => $definition['featured'],
                'is_active' => true,
                'created_at' => now()->subMinutes($index),
                'updated_at' => now()->subMinutes($index),
            ]);
        }
    }

    private function productSpecifications(string $slug): string
    {
        return match ($slug) {
            'iphone-15' => "Chipset: Apple A16 Bionic\nMàn hình: 6.1 inch Super Retina XDR OLED\nCamera sau: 48MP + 12MP\nCamera trước: 12MP TrueDepth\nPin: Lên đến 20 giờ xem video\nCổng sạc: USB-C\nHệ điều hành: iOS",
            'iphone-15-pro' => "Chipset: Apple A17 Pro\nMàn hình: 6.1 inch ProMotion OLED 120Hz\nCamera sau: 48MP chính + 12MP tele + 12MP góc siêu rộng\nKhung: Titanium\nCổng sạc: USB-C 3.0\nPin: Lên đến 23 giờ xem video",
            'iphone-16' => "Chipset: Apple A18\nMàn hình: 6.1 inch Super Retina XDR OLED\nCamera sau: 48MP Fusion + 12MP góc siêu rộng\nNút Camera Control\nCổng sạc: USB-C\nPin: Lên đến 22 giờ xem video",
            'iphone-16-pro' => "Chipset: Apple A18 Pro\nMàn hình: 6.3 inch ProMotion OLED 120Hz\nCamera sau: 48MP Fusion + 48MP góc siêu rộng + 12MP tele 5x\nKhung: Titanium\nCổng sạc: USB-C 3.0\nPin: Lên đến 27 giờ xem video",
            'iphone-16-pro-max' => "Chipset: Apple A18 Pro\nMàn hình: 6.9 inch ProMotion OLED 120Hz\nCamera sau: 48MP Fusion + 48MP góc siêu rộng + 12MP tele 5x\nKhung: Titanium\nCổng sạc: USB-C 3.0\nPin: Lên đến 33 giờ xem video",
            'ipad-10-9' => "Chipset: Apple A14 Bionic\nMàn hình: 10.9 inch Liquid Retina\nBút hỗ trợ: Apple Pencil (thế hệ 1)\nCamera trước: 12MP Center Stage\nCổng sạc: USB-C\nHệ điều hành: iPadOS",
            'ipad-air-m2' => "Chipset: Apple M2\nMàn hình: 11 inch Liquid Retina\nBút hỗ trợ: Apple Pencil Pro / USB-C\nCamera trước: 12MP Center Stage\nCổng sạc: USB-C\nHệ điều hành: iPadOS",
            'ipad-pro-11-m4' => "Chipset: Apple M4\nMàn hình: 11 inch Ultra Retina XDR OLED\nBút hỗ trợ: Apple Pencil Pro\nCamera trước: 12MP Center Stage\nCổng sạc: USB-C (Thunderbolt)\nHệ điều hành: iPadOS",
            'apple-20w-usb-c-adapter' => "Công suất: 20W\nCổng ra: USB-C\nTương thích: iPhone, iPad, AirPods\nChuẩn sạc nhanh: USB Power Delivery\nPhạm vi điện áp: 100–240V",
            'apple-30w-usb-c-adapter' => "Công suất: 30W\nCổng ra: USB-C\nTương thích: iPad, MacBook Air, iPhone\nChuẩn sạc nhanh: USB Power Delivery\nPhạm vi điện áp: 100–240V",
            'usb-c-to-lightning-1m' => "Chiều dài: 1 mét\nĐầu vào: USB-C\nĐầu ra: Lightning\nTương thích: iPhone, AirPods hộp Lightning\nChức năng: Sạc & đồng bộ dữ liệu",
            'usb-c-cable-1m' => "Chiều dài: 1 mét\nĐầu nối: USB-C to USB-C\nTương thích: iPhone 15 trở lên, iPad, Mac\nChức năng: Sạc & truyền dữ liệu\nChuẩn: USB 2.0",
            'airpods-3' => "Chipset: Apple H1\nKiểu tai nghe: Earbuds (không nút silicon)\nÂm thanh: Spatial Audio, Adaptive EQ\nChống nước: IPX4\nPin tai nghe: ~6 giờ\nPin kèm hộp: ~30 giờ\nSạc hộp: MagSafe, Lightning hoặc không dây Qi",
            'airpods-pro-2' => "Chipset: Apple H2\nChống ồn: ANC chủ động\nÂm thanh: Spatial Audio, Adaptive EQ\nChống nước: IP54\nPin tai nghe: ~6 giờ (ANC bật)\nPin kèm hộp: ~30 giờ\nSạc hộp: USB-C MagSafe",
            'airpods-max' => "Chipset: Apple H1\nKiểu tai nghe: Over-ear\nChống ồn: ANC chủ động\nÂm thanh: Spatial Audio cá nhân hóa\nPin: ~20 giờ (ANC bật)\nKết nối: Bluetooth 5.0\nSạc: USB-C",
            default => "Thương hiệu: Apple\nNguồn gốc: Chính hãng\nBảo hành: Theo chính sách cửa hàng",
        };
    }

    private function productDescriptionHtml(string $name, string $slug): string
    {
        $demoImages = [
            'iphone-15' => 'iphone-15-black.webp',
            'iphone-15-pro' => 'iphone-15-pro-natural-titanium.webp',
            'iphone-16' => 'iphone-16-black.webp',
            'iphone-16-pro' => 'iphone-16-pro-black-titanium.webp',
            'iphone-16-pro-max' => 'iphone-16-pro-max-black-titanium.webp',
            'ipad-10-9' => 'ipad-10-9-blue.webp',
            'ipad-air-m2' => 'ipad-air-m2-blue.webp',
            'ipad-pro-11-m4' => 'ipad-pro-11-m4-black.webp',
            'apple-20w-usb-c-adapter' => 'apple-20w-usb-c-adapter.webp',
            'apple-30w-usb-c-adapter' => 'apple-30w-usb-c-adapter.webp',
            'usb-c-to-lightning-1m' => 'usb-c-to-lightning-1m.webp',
            'usb-c-cable-1m' => 'usb-c-cable-1m.webp',
            'airpods-3' => 'airpods-3.webp',
            'airpods-pro-2' => 'airpods-pro-2.webp',
            'airpods-max' => 'airpods-max.webp',
        ];

        $highlights = match ($slug) {
            'iphone-16-pro', 'iphone-16-pro-max' => ['Chip A18 Pro', 'Camera Fusion 48MP', 'Khung titanium', 'USB-C'],
            'iphone-16', 'iphone-15', 'iphone-15-pro' => ['Hiệu năng mạnh mẽ', 'Camera nâng cấp', 'Pin bền', 'iOS mới nhất'],
            'ipad-air-m2', 'ipad-pro-11-m4', 'ipad-10-9' => ['Màn hình sắc nét', 'Hỗ trợ Apple Pencil', 'Pin cả ngày', 'iPadOS'],
            'airpods-3' => ['Spatial Audio', 'Chip H1', 'Chống nước IPX4', 'Hộp sạc MagSafe'],
            'airpods-pro-2' => ['Chống ồn ANC', 'Chip H2', 'Spatial Audio', 'Hộp sạc USB-C'],
            'airpods-max' => ['Tai nghe over-ear', 'Chống ồn cao cấp', 'Âm thanh không gian', 'Pin 20 giờ'],
            default => ['Chính hãng Apple', 'Bảo hành cửa hàng', 'Phù hợp đồ án', 'Giao hàng toàn quốc'],
        };

        $listItems = collect($highlights)
            ->map(static fn (string $item): string => '<li>'.e($item).'</li>')
            ->implode('');

        $html = '<h2>Giới thiệu</h2>';
        $html .= '<p>'.e($name).' là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p>';
        $html .= '<h3>Điểm nổi bật</h3><ul>'.$listItems.'</ul>';
        $html .= '<h3>Thông số nhanh</h3>';
        $html .= '<table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody>';
        $html .= '<tr><td>Thương hiệu</td><td>Apple</td></tr>';
        $html .= '<tr><td>Phân loại</td><td>'.e($name).'</td></tr>';
        $html .= '<tr><td>Mục đích</td><td>Học tập / demo</td></tr>';
        $html .= '</tbody></table>';

        if (isset($demoImages[$slug]) && is_file(storage_path('app/public/products/demo/'.$demoImages[$slug]))) {
            $src = \Illuminate\Support\Facades\Storage::disk('public')->url('products/demo/'.$demoImages[$slug]);
            $html .= '<h3>Hình ảnh minh họa</h3>';
            $html .= '<p><img src="'.e($src).'" alt="'.e($name).'" loading="lazy"></p>';
        }

        if (in_array($slug, ['iphone-16-pro', 'ipad-air-m2'], true)) {
            $html .= ProductDescriptionYoutube::embedHtml('aqz-KE-bpKQ', 'Video giới thiệu '.$name);
        }

        return $html;
    }

    /**
     * @return array<string, list<array{file: string, alt: string, primary: bool}>>
     */
    private function productImageDefinitions(): array
    {
        $fallback = $this->fallbackProductImageDefinitions();
        $manifestPath = storage_path('app/public/products/demo/fetch-manifest.json');

        if (! is_file($manifestPath)) {
            return $fallback;
        }

        /** @var array<string, list<array{file: string, alt: string, primary: bool}>>|null $manifest */
        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (! is_array($manifest)) {
            return $fallback;
        }

        $definitions = [];

        foreach ($fallback as $slug => $defaultImages) {
            $images = $manifest[$slug] ?? [];

            if ($images === []) {
                $definitions[$slug] = $defaultImages;

                continue;
            }

            $definitions[$slug] = collect($images)
                ->filter(fn (array $image): bool => is_file(storage_path('app/public/products/demo/'.$image['file'])))
                ->map(fn (array $image): array => [
                    'file' => $image['file'],
                    'alt' => (string) $image['alt'],
                    'primary' => (bool) ($image['primary'] ?? false),
                ])
                ->values()
                ->all();

            if ($definitions[$slug] === []) {
                $definitions[$slug] = $defaultImages;
            }
        }

        return $definitions;
    }

    /**
     * @return array<string, list<array{file: string, alt: string, primary: bool}>>
     */
    private function fallbackProductImageDefinitions(): array
    {
        return [
            'iphone-15' => [
                ['file' => 'iphone-15-black.webp', 'alt' => 'iPhone 15 màu đen', 'primary' => true],
            ],
            'iphone-15-pro' => [
                ['file' => 'iphone-15-pro-natural-titanium.webp', 'alt' => 'iPhone 15 Pro màu Titanium tự nhiên', 'primary' => true],
            ],
            'iphone-16' => [
                ['file' => 'iphone-16-black.webp', 'alt' => 'iPhone 16 màu đen', 'primary' => true],
            ],
            'iphone-16-pro' => [
                ['file' => 'iphone-16-pro-black-titanium.webp', 'alt' => 'iPhone 16 Pro màu Titanium đen', 'primary' => true],
            ],
            'iphone-16-pro-max' => [
                ['file' => 'iphone-16-pro-max-black-titanium.webp', 'alt' => 'iPhone 16 Pro Max màu Titanium đen', 'primary' => true],
            ],
            'ipad-10-9' => [
                ['file' => 'ipad-10-9-blue.webp', 'alt' => 'iPad 10.9 inch màu xanh dương', 'primary' => true],
            ],
            'ipad-air-m2' => [
                ['file' => 'ipad-air-m2-blue.webp', 'alt' => 'iPad Air M2 màu xanh dương', 'primary' => true],
            ],
            'ipad-pro-11-m4' => [
                ['file' => 'ipad-pro-11-m4-black.webp', 'alt' => 'iPad Pro 11 inch M4 màu đen', 'primary' => true],
            ],
            'apple-20w-usb-c-adapter' => [
                ['file' => 'apple-20w-usb-c-adapter.webp', 'alt' => 'Củ sạc Apple 20W USB-C', 'primary' => true],
            ],
            'apple-30w-usb-c-adapter' => [
                ['file' => 'apple-30w-usb-c-adapter.webp', 'alt' => 'Củ sạc Apple 30W USB-C', 'primary' => true],
            ],
            'usb-c-to-lightning-1m' => [
                ['file' => 'usb-c-to-lightning-1m.webp', 'alt' => 'Cáp USB-C sang Lightning 1m', 'primary' => true],
            ],
            'usb-c-cable-1m' => [
                ['file' => 'usb-c-cable-1m.webp', 'alt' => 'Cáp USB-C 1m', 'primary' => true],
            ],
            'airpods-3' => [
                ['file' => 'airpods-3.webp', 'alt' => 'AirPods (thế hệ 3)', 'primary' => true],
            ],
            'airpods-pro-2' => [
                ['file' => 'airpods-pro-2.webp', 'alt' => 'AirPods Pro (thế hệ 2)', 'primary' => true],
            ],
            'airpods-max' => [
                ['file' => 'airpods-max-black.webp', 'alt' => 'AirPods Max màu đen', 'primary' => true],
            ],
        ];
    }

    private function seedProductImages(): void
    {
        foreach ($this->productImageDefinitions() as $slug => $images) {
            $product = $this->findProductBySlug($slug);
            $sortOrder = 1;
            $hasPrimary = false;

            foreach ($images as $imageDefinition) {
                $relativePath = 'products/demo/'.$imageDefinition['file'];
                $absolutePath = storage_path('app/public/'.$relativePath);

                if (! is_file($absolutePath)) {
                    continue;
                }

                $isPrimary = (bool) ($imageDefinition['primary'] ?? false);

                ProductImage::query()->create([
                    'product_id' => $product->id,
                    'path' => $relativePath,
                    'alt_text' => $imageDefinition['alt'],
                    'sort_order' => $sortOrder,
                    'is_primary' => $isPrimary,
                ]);

                $hasPrimary = $hasPrimary || $isPrimary;
                $sortOrder++;
            }

            if ($sortOrder > 1 && ! $hasPrimary) {
                ProductImage::query()
                    ->where('product_id', $product->id)
                    ->orderBy('sort_order')
                    ->limit(1)
                    ->update(['is_primary' => true]);
            }
        }
    }

    private function seedVariants(): void
    {
        $iphoneConfigs = [
            ['product' => 'iphone-15', 'prefix' => 'IP15', 'base' => 18_990_000],
            ['product' => 'iphone-15-pro', 'prefix' => 'IP15P', 'base' => 24_990_000],
            ['product' => 'iphone-16', 'prefix' => 'IP16', 'base' => 19_990_000],
            ['product' => 'iphone-16-pro', 'prefix' => 'IP16P', 'base' => 25_990_000],
            ['product' => 'iphone-16-pro-max', 'prefix' => 'IP16PM', 'base' => 29_990_000],
        ];

        $iphoneColorMap = [
            'iphone-15' => ['black', 'blue', 'pink'],
            'iphone-15-pro' => ['natural-titanium', 'black-titanium', 'white-titanium'],
            'iphone-16' => ['black', 'blue', 'pink'],
            'iphone-16-pro' => ['black-titanium', 'natural-titanium', 'white-titanium'],
            'iphone-16-pro-max' => ['black-titanium', 'natural-titanium', 'desert-titanium'],
        ];
        $iphoneStorages = [128, 256, 512];

        foreach ($iphoneConfigs as $config) {
            $product = $this->findProductBySlug($config['product']);

            foreach ($iphoneColorMap[$config['product']] as $colorSlug) {
                foreach ($iphoneStorages as $storageGb) {
                    $storageStep = array_search($storageGb, [128, 256, 512], true);
                    $salePrice = $config['base'] + ($storageStep * 4_000_000);

                    $this->variants[] = $this->createVariant(
                        product: $product,
                        sku: $config['prefix'].'-'.$this->colorSkuCode($colorSlug).'-'.$storageGb,
                        color: $this->colors[$colorSlug],
                        storage: $this->storages[$storageGb],
                        salePrice: $salePrice,
                        originalPrice: $salePrice + 1_000_000,
                        stock: fake()->numberBetween(5, 30),
                    );
                }
            }
        }

        $ipadConfigs = [
            ['product' => 'ipad-10-9', 'prefix' => 'IPAD10', 'base' => 9_990_000, 'storages' => [64, 256]],
            ['product' => 'ipad-air-m2', 'prefix' => 'IPADAIR', 'base' => 14_990_000, 'storages' => [128, 256, 512]],
            ['product' => 'ipad-pro-11-m4', 'prefix' => 'IPADP11', 'base' => 22_990_000, 'storages' => [256, 512, 1024]],
        ];

        $ipadColorMap = [
            'ipad-10-9' => ['blue', 'pink', 'white'],
            'ipad-air-m2' => ['blue', 'purple', 'white'],
            'ipad-pro-11-m4' => ['black', 'white'],
        ];

        foreach ($ipadConfigs as $config) {
            $product = $this->findProductBySlug($config['product']);

            foreach ($ipadColorMap[$config['product']] as $colorSlug) {
                foreach ($config['storages'] as $index => $storageGb) {
                    $salePrice = $config['base'] + ($index * 3_000_000);

                    $this->variants[] = $this->createVariant(
                        product: $product,
                        sku: $config['prefix'].'-'.$this->colorSkuCode($colorSlug).'-'.$storageGb,
                        color: $this->colors[$colorSlug],
                        storage: $this->storages[$storageGb],
                        salePrice: $salePrice,
                        originalPrice: null,
                        stock: fake()->numberBetween(3, 20),
                    );
                }
            }
        }

        $accessoryVariants = [
            ['product' => 'apple-20w-usb-c-adapter', 'sku' => 'CHG20W-WHT', 'price' => 490_000, 'color' => 'white'],
            ['product' => 'apple-30w-usb-c-adapter', 'sku' => 'CHG30W-WHT', 'price' => 790_000, 'color' => 'white'],
            ['product' => 'usb-c-to-lightning-1m', 'sku' => 'CBL-CL-1M', 'price' => 450_000, 'color' => 'white'],
            ['product' => 'usb-c-cable-1m', 'sku' => 'CBL-CC-1M', 'price' => 390_000, 'color' => 'white'],
        ];

        foreach ($accessoryVariants as $accessory) {
            $this->variants[] = $this->createVariant(
                product: $this->findProductBySlug($accessory['product']),
                sku: $accessory['sku'],
                color: $this->colors[$accessory['color']],
                storage: null,
                salePrice: $accessory['price'],
                originalPrice: null,
                stock: fake()->numberBetween(20, 100),
            );
        }

        $airpodsVariants = [
            ['product' => 'airpods-3', 'sku' => 'APOD3-WHT', 'price' => 4_290_000, 'color' => 'white'],
            ['product' => 'airpods-pro-2', 'sku' => 'APODP2-WHT', 'price' => 5_990_000, 'color' => 'white'],
        ];

        foreach ($airpodsVariants as $airpods) {
            $this->variants[] = $this->createVariant(
                product: $this->findProductBySlug($airpods['product']),
                sku: $airpods['sku'],
                color: $this->colors[$airpods['color']],
                storage: null,
                salePrice: $airpods['price'],
                originalPrice: null,
                stock: fake()->numberBetween(15, 50),
            );
        }

        $airpodsMax = $this->findProductBySlug('airpods-max');
        foreach (['black', 'blue'] as $colorSlug) {
            $this->variants[] = $this->createVariant(
                product: $airpodsMax,
                sku: 'APODMAX-'.$this->colorSkuCode($colorSlug),
                color: $this->colors[$colorSlug],
                storage: null,
                salePrice: 12_990_000,
                originalPrice: 13_990_000,
                stock: fake()->numberBetween(5, 20),
            );
        }
    }

    private function createVariant(
        Product $product,
        string $sku,
        ?Color $color,
        ?StorageOption $storage,
        int $salePrice,
        ?int $originalPrice,
        int $stock,
    ): ProductVariant {
        return ProductVariant::query()->create([
            'product_id' => $product->id,
            'color_id' => $color?->id,
            'storage_option_id' => $storage?->id,
            'sku' => $sku,
            'original_price' => $originalPrice,
            'sale_price' => $salePrice,
            'stock_quantity' => $stock,
            'is_active' => true,
        ]);
    }

    private function seedOrders(): void
    {
        $statuses = [
            OrderStatus::Pending,
            OrderStatus::Pending,
            OrderStatus::Confirmed,
            OrderStatus::Confirmed,
            OrderStatus::Shipping,
            OrderStatus::Shipping,
            OrderStatus::Completed,
            OrderStatus::Completed,
            OrderStatus::Cancelled,
            OrderStatus::Cancelled,
        ];

        foreach ($statuses as $index => $status) {
            $customer = $this->customers[$index % count($this->customers)];
            $selectedVariants = collect($this->variants)->random(min(2, count($this->variants)));

            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedVariants as $variant) {
                $variant->loadMissing(['product', 'color', 'storageOption']);
                $quantity = fake()->numberBetween(1, 2);
                $lineTotal = $variant->sale_price * $quantity;
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }

            $shippingFee = $subtotal >= 10_000_000 ? 0 : 30_000;

            $order = Order::query()->create([
                'order_code' => 'ORD-'.now()->format('ymd').'-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'receiver_name' => $customer->name,
                'receiver_phone' => $customer->phone,
                'province' => 'TP. Hồ Chí Minh',
                'district' => 'Quận '.(($index % 12) + 1),
                'ward' => 'Phường '.fake()->numberBetween(1, 20),
                'address_line' => fake()->streetAddress(),
                'note' => fake()->optional()->sentence(),
                'payment_method' => 'cod',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $subtotal + $shippingFee,
                'status' => $status,
                'cancelled_at' => $status === OrderStatus::Cancelled ? now()->subDays(1) : null,
                'completed_at' => $status === OrderStatus::Completed ? now()->subDays(2) : null,
            ]);

            foreach ($itemsData as $item) {
                /** @var ProductVariant $variant */
                $variant = $item['variant'];

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'sku' => $variant->sku,
                    'color_name' => $variant->color?->name ?? 'Không có',
                    'storage_label' => $variant->storageOption?->label ?? 'Không có',
                    'unit_price' => $variant->sale_price,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }
        }
    }

    private function findProductBySlug(string $slug): Product
    {
        foreach ($this->products as $product) {
            if ($product->slug === $slug) {
                return $product;
            }
        }

        throw new \RuntimeException("Product [{$slug}] not found in seeder.");
    }

    private function colorSkuCode(string $colorSlug): string
    {
        return match ($colorSlug) {
            'black' => 'BLK',
            'white' => 'WHT',
            'blue' => 'BLU',
            'pink' => 'PNK',
            'purple' => 'PUR',
            'green' => 'GRN',
            'natural-titanium' => 'NTI',
            'black-titanium' => 'BTI',
            'white-titanium' => 'WTI',
            'desert-titanium' => 'DTI',
            default => strtoupper(substr(str_replace('-', '', $colorSlug), 0, 3)),
        };
    }
}
