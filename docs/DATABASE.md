# THIẾT KẾ CƠ SỞ DỮ LIỆU

Tài liệu mô tả schema **đã triển khai** trong dự án iStore (Laravel 13 + MySQL). Cập nhật theo `database/migrations/` và `CatalogSeeder` (tháng 6/2026).

**Database demo:** `apple_store` (utf8mb4 / utf8mb4_unicode_ci)

---

## 1. Nguyên tắc

- MySQL 8 hoặc phiên bản tương thích với Laravel đang dùng.
- InnoDB.
- UTF-8 đầy đủ (`utf8mb4`).
- Có khóa ngoại trên các bảng nghiệp vụ chính.
- Có index cho truy vấn lọc / phân trang.
- Tiền lưu bằng `BIGINT UNSIGNED` theo đơn vị VNĐ (không dùng float).
- `order_items` lưu snapshot — không phụ thuộc giá/tên sau khi đặt hàng.
- Tồn kho nằm ở `product_variants.stock_quantity`.
- `products` dùng soft delete (`deleted_at`).
- Không dùng EAV.

**Chưa có trong database (phase nâng cao / tương lai):** `wishlists`, `product_reviews`, `coupons`, `audit_logs`, `cancelled_by` / `cancel_reason` trên `orders` — xem `docs/ADVANCED_FEATURES_SPEC.md`.

### 1.1. Tiền tệ

Toàn bộ giá và tổng tiền dùng Việt Nam đồng.

Giá trị database:

    19990000

Hiển thị (PHP `VndMoney` / Blade):

    19.990.000 ₫

Database không lưu ký hiệu tiền tệ hoặc dấu phân cách hàng nghìn.

Cấu hình phí ship (không nằm trong DB): `config/store.php` — `SHIPPING_FEE`, `SHIPPING_FREE_THRESHOLD`, `LOW_STOCK_THRESHOLD`.

---

## 2. Bảng nghiệp vụ

### 2.1. `users`

| Cột | Kiểu (migration) | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `name` | VARCHAR(100) | NOT NULL |
| `email` | VARCHAR(150) | UNIQUE, NOT NULL |
| `phone` | VARCHAR(20) | UNIQUE, NOT NULL |
| `email_verified_at` | TIMESTAMP | NULL |
| `password` | VARCHAR(255) | NOT NULL |
| `role` | VARCHAR(20) | default `customer`, INDEX |
| `status` | VARCHAR(20) | default `active`, INDEX |
| `default_address` | VARCHAR(500) | NULL |
| `remember_token` | VARCHAR(100) | NULL |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Giá trị enum (`App\Enums`):

| Cột | Giá trị |
|---|---|
| `role` | `customer`, `admin` |
| `status` | `active`, `blocked` |

Quy tắc: role không nhận từ form đăng ký; admin middleware kiểm tra `role = admin`.

### 2.2. `categories`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `name` | VARCHAR(100) | NOT NULL |
| `slug` | VARCHAR(120) | UNIQUE |
| `description` | TEXT | NULL |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `(is_active, sort_order)`.

### 2.3. `product_series`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `category_id` | BIGINT UNSIGNED | FK → `categories`, RESTRICT |
| `name` | VARCHAR(120) | NOT NULL |
| `slug` | VARCHAR(140) | UNIQUE |
| `release_year` | SMALLINT UNSIGNED | NULL |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `(category_id, is_active)`, `sort_order`.

### 2.4. `products`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `category_id` | BIGINT UNSIGNED | FK → `categories`, RESTRICT |
| `product_series_id` | BIGINT UNSIGNED | FK → `product_series`, NULL, SET NULL |
| `name` | VARCHAR(160) | NOT NULL |
| `slug` | VARCHAR(180) | UNIQUE |
| `short_description` | VARCHAR(500) | NULL — plain text |
| `description` | LONGTEXT | NULL — HTML đã sanitize (Quill) |
| `specifications` | LONGTEXT | NULL — plain text |
| `release_year` | SMALLINT UNSIGNED | NULL |
| `is_featured` | BOOLEAN | default false |
| `is_active` | BOOLEAN | default true |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | NULL — soft delete |

Index: `category_id`, `product_series_id`, `release_year`, `(is_active, is_featured)`, `created_at`.

**`description` (Phase 12 — WYSIWYG):**

- Admin nhập bằng Quill; lưu HTML sau `ProductDescriptionSanitizer::prepare()`.
- Cho phép: heading, list, bảng, link, ảnh (`/storage/...`), embed YouTube (`div.video-embed` + `iframe`).
- Ảnh trong mô tả upload vào `storage/app/public/products/description/` — **không** có bảng riêng; chỉ lưu URL trong HTML.
- Khách xem qua `ProductDescriptionSanitizer::prepare()` khi render.

### 2.5. `product_images`

Ảnh gallery sản phẩm (thumbnail, carousel, admin gallery) — tách khỏi ảnh nhúng trong `description`.

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `product_id` | BIGINT UNSIGNED | FK → `products`, CASCADE |
| `path` | VARCHAR(255) | NOT NULL — path tương đối disk `public` |
| `alt_text` | VARCHAR(180) | NULL |
| `sort_order` | INT UNSIGNED | default 0 |
| `is_primary` | BOOLEAN | default false |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `(product_id, is_primary)`, `(product_id, sort_order)`.

Quy tắc ứng dụng:

- Tối đa một ảnh `is_primary` / product (logic trong `ProductImageController`).
- Không có primary → dùng ảnh `sort_order` thấp nhất.
- Không có record → placeholder `public/images/placeholders/product-placeholder.svg`.
- `ProductImageUrl` từ chối URL `http(s)://` — chỉ path local.

**Quy ước path:**

| Loại | Ví dụ path trong DB / HTML |
|---|---|
| Gallery admin upload | `products/{product_id}/{ulid}.webp` |
| Ảnh demo seed | `products/demo/iphone-16-pro-black-titanium.webp` |
| Ảnh trong mô tả Quill | `products/description/{ulid}.webp` |

Không lưu domain đầy đủ trong `product_images.path`.

### 2.6. `colors`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `name` | VARCHAR(60) | NOT NULL |
| `slug` | VARCHAR(80) | UNIQUE |
| `hex_code` | CHAR(7) | NULL — chỉ hiển thị UI |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `(is_active, sort_order)`.

### 2.7. `storage_options`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `label` | VARCHAR(30) | NOT NULL |
| `capacity_gb` | INT UNSIGNED | UNIQUE |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `(is_active, sort_order)`.

Seed: 64, 128, 256, 512, 1024 (GB).

### 2.8. `product_variants`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `product_id` | BIGINT UNSIGNED | FK → `products`, RESTRICT |
| `color_id` | BIGINT UNSIGNED | FK → `colors`, NULL, SET NULL |
| `storage_option_id` | BIGINT UNSIGNED | FK → `storage_options`, NULL, SET NULL |
| `sku` | VARCHAR(60) | UNIQUE |
| `original_price` | BIGINT UNSIGNED | NULL |
| `sale_price` | BIGINT UNSIGNED | NOT NULL |
| `stock_quantity` | INT UNSIGNED | default 0 |
| `is_active` | BOOLEAN | default true |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `product_id`, `color_id`, `storage_option_id`, `sale_price`, `(is_active, stock_quantity)`.

Quy tắc:

- `sale_price >= 0`, `stock_quantity >= 0`.
- Nếu có `original_price` thì `sale_price <= original_price`.
- Phụ kiện có thể `color_id` / `storage_option_id` NULL.
- Validation ngăn trùng tổ hợp (product + color + storage) trong Form Request / service.
- Không hard delete variant đã có `order_items` (ứng dụng deactivate).

### 2.9. `orders`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `order_code` | VARCHAR(30) | UNIQUE |
| `user_id` | BIGINT UNSIGNED | FK → `users`, RESTRICT |
| `receiver_name` | VARCHAR(100) | NOT NULL |
| `receiver_phone` | VARCHAR(20) | NOT NULL |
| `province` | VARCHAR(100) | NOT NULL |
| `district` | VARCHAR(100) | NOT NULL |
| `ward` | VARCHAR(100) | NOT NULL |
| `address_line` | VARCHAR(255) | NOT NULL |
| `note` | VARCHAR(500) | NULL |
| `payment_method` | VARCHAR(20) | default `cod` |
| `subtotal` | BIGINT UNSIGNED | NOT NULL |
| `shipping_fee` | BIGINT UNSIGNED | default 0 |
| `total_amount` | BIGINT UNSIGNED | NOT NULL |
| `status` | VARCHAR(20) | default `pending` |
| `cancelled_at` | TIMESTAMP | NULL |
| `completed_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `user_id`, `order_code`, `status`, `created_at`, `(status, created_at)`.

Trạng thái (`App\Enums\OrderStatus`):

| Giá trị | Nhãn UI |
|---|---|
| `pending` | Chờ xác nhận |
| `confirmed` | Đã xác nhận |
| `shipping` | Đang giao |
| `completed` | Hoàn thành |
| `cancelled` | Đã hủy |

Luồng admin: `pending → confirmed → shipping → completed`. Hủy: `pending` hoặc `confirmed` (hoàn tồn kho một lần).

Quy tắc: `total_amount = subtotal + shipping_fee` — server tính, client không tin cậy.

### 2.10. `order_items`

| Cột | Kiểu | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `order_id` | BIGINT UNSIGNED | FK → `orders`, CASCADE |
| `product_id` | BIGINT UNSIGNED | FK → `products`, NULL, SET NULL |
| `product_variant_id` | BIGINT UNSIGNED | FK → `product_variants`, NULL, SET NULL |
| `product_name` | VARCHAR(160) | NOT NULL — snapshot |
| `sku` | VARCHAR(60) | NOT NULL — snapshot |
| `color_name` | VARCHAR(60) | NOT NULL — snapshot |
| `storage_label` | VARCHAR(30) | NOT NULL — snapshot |
| `unit_price` | BIGINT UNSIGNED | NOT NULL — snapshot |
| `quantity` | INT UNSIGNED | NOT NULL |
| `line_total` | BIGINT UNSIGNED | NOT NULL |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index: `order_id`, `product_id`, `product_variant_id`.

Quy tắc: `line_total = unit_price * quantity`. Cột snapshot không đổi khi sửa catalog.

---

## 3. Bảng hệ thống Laravel

Tạo bởi migration mặc định Laravel — không chứa dữ liệu nghiệp vụ:

| Bảng | Mục đích |
|---|---|
| `password_reset_tokens` | Reset mật khẩu |
| `sessions` | Session driver database (nếu cấu hình) |
| `cache`, `cache_locks` | Cache driver database |
| `jobs`, `job_batches`, `failed_jobs` | Queue (chưa dùng email/queue trong MVP) |

---

## 4. ERD (văn bản)

```
users 1 ───── n orders
orders 1 ───── n order_items

categories 1 ───── n product_series
categories 1 ───── n products
product_series 1 ───── n products

products 1 ───── n product_images
products 1 ───── n product_variants

colors 1 ───── n product_variants
storage_options 1 ───── n product_variants

products 1 ───── n order_items        (nullable FK, snapshot vẫn giữ)
product_variants 1 ───── n order_items (nullable FK)
```

---

## 5. Chính sách khóa ngoại (đã áp dụng)

| FK | ON DELETE |
|---|---|
| `product_series.category_id` | RESTRICT |
| `products.category_id` | RESTRICT |
| `products.product_series_id` | SET NULL |
| `product_images.product_id` | CASCADE |
| `product_variants.product_id` | RESTRICT |
| `product_variants.color_id` | SET NULL |
| `product_variants.storage_option_id` | SET NULL |
| `orders.user_id` | RESTRICT |
| `order_items.order_id` | CASCADE |
| `order_items.product_id` | SET NULL |
| `order_items.product_variant_id` | SET NULL |

Production: không xóa `orders` / `users` có đơn; product deactivate hoặc soft delete.

---

## 6. Seeder hiện tại (`CatalogSeeder`)

Chạy: `php artisan migrate:fresh --seed` (+ `php artisan storage:link` cho ảnh).

| Dữ liệu | Số lượng | Ghi chú |
|---|---:|---|
| Admin | 1 | `admin@istore.test` / `password` |
| Khách hàng | 5 | `customer1@istore.test` … `customer5@istore.test` |
| Categories | 4 | iPhone, iPad, iPod, Phụ kiện sạc |
| Product series | 8 | iPhone 15/16, iPad, iPad Air/Pro, iPod, chargers, cables |
| Colors | 10 | Đen, Trắng, Titanium, … |
| Storage options | 5 | 64 GB → 1 TB |
| Products | 13 | 5 iPhone, 3 iPad, 1 iPod, 4 phụ kiện |
| Product images | ≤ 13 | Bỏ qua nếu file demo không có trong `storage/app/public/products/demo/` |
| Product variants | ~105 | iPhone × màu × dung lượng + iPad + iPod + phụ kiện |
| Orders | 10 | 2 mỗi trạng thái: pending, confirmed, shipping, completed, cancelled |

**Mô tả sản phẩm seed:** HTML tương thích Quill (heading, list, bảng thông số, ảnh demo, YouTube trên iPhone 16 Pro và iPad Air M2).

**Ảnh demo:** `storage/app/public/products/demo/*.webp` (xem `todo/NEEDS_HELP.md`).

---

## 7. Truy vấn product discovery

`App\Queries\ProductQuery` phải:

- Chỉ product `is_active = true` và chưa soft delete.
- Eager load ảnh primary / sort_order thấp nhất.
- Lọc variant qua `whereHas` / subquery (category, series, color, storage, giá).
- Giá hiển thị từ min `sale_price` của variant active.
- Phân trang ở database; giữ query string khi lọc.

Không:

- `Product::all()` rồi filter collection.
- Query N+1 ảnh/variant trong Blade.
- Cho client chọn cột `ORDER BY` tùy ý.

---

## 8. Liên quan

| Tài liệu | Nội dung |
|---|---|
| [`docs/SPEC.md`](SPEC.md) | Đặc tả nghiệp vụ |
| [`docs/IMAGE_STRATEGY.md`](IMAGE_STRATEGY.md) | Chiến lược ảnh sản phẩm |
| [`docs/ADVANCED_FEATURES_SPEC.md`](ADVANCED_FEATURES_SPEC.md) | Bảng / tính năng phase nâng cao |
| [`todo/script.sql`](todo/script.sql) | Dump MySQL tùy chọn |
