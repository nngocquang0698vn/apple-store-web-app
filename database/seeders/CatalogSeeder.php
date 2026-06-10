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
            ['name' => 'iPod', 'slug' => 'ipod', 'sort_order' => 3],
            ['name' => 'Phụ kiện sạc', 'slug' => 'phu-kien-sac', 'sort_order' => 4],
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
            ['category' => 'ipod', 'name' => 'iPod touch', 'slug' => 'ipod-touch', 'release_year' => 2019],
            ['category' => 'phu-kien-sac', 'name' => 'USB-C Chargers', 'slug' => 'usb-c-chargers', 'release_year' => 2023],
            ['category' => 'phu-kien-sac', 'name' => 'Charging Cables', 'slug' => 'charging-cables', 'release_year' => 2023],
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
            ['name' => 'iPod touch (thế hệ 7)', 'slug' => 'ipod-touch-gen-7', 'series' => 'ipod-touch', 'category' => 'ipod', 'featured' => false, 'year' => 2019],
            ['name' => 'Apple 20W USB-C Power Adapter', 'slug' => 'apple-20w-usb-c-adapter', 'series' => 'usb-c-chargers', 'category' => 'phu-kien-sac', 'featured' => true, 'year' => 2023],
            ['name' => 'Apple 30W USB-C Power Adapter', 'slug' => 'apple-30w-usb-c-adapter', 'series' => 'usb-c-chargers', 'category' => 'phu-kien-sac', 'featured' => false, 'year' => 2023],
            ['name' => 'Cáp USB-C sang Lightning 1m', 'slug' => 'usb-c-to-lightning-1m', 'series' => 'charging-cables', 'category' => 'phu-kien-sac', 'featured' => false, 'year' => 2023],
            ['name' => 'Cáp USB-C 1m', 'slug' => 'usb-c-cable-1m', 'series' => 'charging-cables', 'category' => 'phu-kien-sac', 'featured' => false, 'year' => 2023],
        ];

        foreach ($definitions as $definition) {
            $this->products[] = Product::query()->create([
                'category_id' => $this->categories[$definition['category']]->id,
                'product_series_id' => $this->series[$definition['series']]->id,
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'short_description' => "{$definition['name']} chính hãng, bảo hành theo chính sách cửa hàng.",
                'description' => "Mô tả demo cho {$definition['name']}. Sản phẩm dùng cho mục đích học tập.",
                'specifications' => null,
                'release_year' => $definition['year'],
                'is_featured' => $definition['featured'],
                'is_active' => true,
            ]);
        }
    }

    private function seedProductImages(): void
    {
        $definitions = [
            ['slug' => 'iphone-15', 'file' => 'iphone-15-black.webp', 'alt' => 'iPhone 15 màu đen'],
            ['slug' => 'iphone-15-pro', 'file' => 'iphone-15-pro-natural-titanium.webp', 'alt' => 'iPhone 15 Pro màu Titanium tự nhiên'],
            ['slug' => 'iphone-16', 'file' => 'iphone-16-black.webp', 'alt' => 'iPhone 16 màu đen'],
            ['slug' => 'iphone-16-pro', 'file' => 'iphone-16-pro-black-titanium.webp', 'alt' => 'iPhone 16 Pro màu Titanium đen'],
            ['slug' => 'iphone-16-pro-max', 'file' => 'iphone-16-pro-max-black-titanium.webp', 'alt' => 'iPhone 16 Pro Max màu Titanium đen'],
            ['slug' => 'ipad-10-9', 'file' => 'ipad-10-9-blue.webp', 'alt' => 'iPad 10.9 inch màu xanh dương'],
            ['slug' => 'ipad-air-m2', 'file' => 'ipad-air-m2-blue.webp', 'alt' => 'iPad Air M2 màu xanh dương'],
            ['slug' => 'ipad-pro-11-m4', 'file' => 'ipad-pro-11-m4-black.webp', 'alt' => 'iPad Pro 11 inch M4 màu đen'],
            ['slug' => 'ipod-touch-gen-7', 'file' => 'ipod-touch-gen-7-blue.webp', 'alt' => 'iPod touch thế hệ 7 màu xanh dương'],
            ['slug' => 'apple-20w-usb-c-adapter', 'file' => 'apple-20w-usb-c-adapter.webp', 'alt' => 'Củ sạc Apple 20W USB-C'],
            ['slug' => 'apple-30w-usb-c-adapter', 'file' => 'apple-30w-usb-c-adapter.webp', 'alt' => 'Củ sạc Apple 30W USB-C'],
            ['slug' => 'usb-c-to-lightning-1m', 'file' => 'usb-c-to-lightning-1m.webp', 'alt' => 'Cáp USB-C sang Lightning 1m'],
            ['slug' => 'usb-c-cable-1m', 'file' => 'usb-c-cable-1m.webp', 'alt' => 'Cáp USB-C 1m'],
        ];

        foreach ($definitions as $index => $definition) {
            $relativePath = 'products/demo/'.$definition['file'];
            $absolutePath = storage_path('app/public/'.$relativePath);

            if (! is_file($absolutePath)) {
                continue;
            }

            ProductImage::query()->create([
                'product_id' => $this->findProductBySlug($definition['slug'])->id,
                'path' => $relativePath,
                'alt_text' => $definition['alt'],
                'sort_order' => $index + 1,
                'is_primary' => true,
            ]);
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

        $iphoneColors = ['black', 'blue', 'pink', 'natural-titanium', 'black-titanium'];
        $iphoneStorages = [128, 256, 512];

        foreach ($iphoneConfigs as $config) {
            $product = $this->findProductBySlug($config['product']);

            foreach ($iphoneColors as $colorSlug) {
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

        foreach ($ipadConfigs as $config) {
            $product = $this->findProductBySlug($config['product']);

            foreach (['black', 'white', 'blue'] as $colorSlug) {
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

        $ipod = $this->findProductBySlug('ipod-touch-gen-7');
        foreach ([64, 128] as $index => $storageGb) {
            $storage = $this->storages[$storageGb];
            $this->variants[] = $this->createVariant(
                product: $ipod,
                sku: 'IPOD-'.strtoupper((string) $storageGb),
                color: $this->colors['blue'],
                storage: $storage,
                salePrice: 6_990_000 + ($index * 1_000_000),
                originalPrice: null,
                stock: fake()->numberBetween(2, 10),
            );
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
