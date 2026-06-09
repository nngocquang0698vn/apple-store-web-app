# THIẾT KẾ CƠ SỞ DỮ LIỆU

## 1. Nguyên tắc

- MySQL 8 hoặc phiên bản tương thích với Laravel đang dùng.
- InnoDB.
- UTF-8 đầy đủ.
- Có khóa ngoại.
- Có index cho truy vấn chính.
- Tiền lưu bằng `BIGINT UNSIGNED` theo đơn vị VNĐ để tránh sai số float.
- Order item lưu snapshot.
- Tồn kho nằm ở product variant.
- Không dùng EAV.
- Không có bảng review hoặc comment.

## 1.1. Tiền tệ

Toàn bộ giá và tổng tiền dùng Việt Nam đồng.

Giá trị database:

    19990000

Hiển thị:

    19.990.000 ₫

Database không lưu ký hiệu tiền tệ hoặc dấu phân cách hàng nghìn.

## 2. Bảng `users`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `name` | VARCHAR(100) | NOT NULL |
| `email` | VARCHAR(150) | UNIQUE, NOT NULL |
| `phone` | VARCHAR(20) | UNIQUE, NOT NULL |
| `email_verified_at` | TIMESTAMP | NULL |
| `password` | VARCHAR(255) | NOT NULL |
| `role` | VARCHAR(20) | default `customer` |
| `status` | VARCHAR(20) | default `active` |
| `default_address` | VARCHAR(500) | NULL |
| `remember_token` | VARCHAR(100) | NULL |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Giá trị:

- role: `customer`, `admin`
- status: `active`, `blocked`

Không dùng role gửi từ form đăng ký.

## 3. Bảng `categories`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `name` | VARCHAR(100) | NOT NULL |
| `slug` | VARCHAR(120) | UNIQUE |
| `description` | TEXT | NULL |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

## 4. Bảng `product_series`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `category_id` | BIGINT UNSIGNED | FK |
| `name` | VARCHAR(120) | NOT NULL |
| `slug` | VARCHAR(140) | UNIQUE |
| `release_year` | SMALLINT UNSIGNED | NULL |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Quan hệ:

- Category có nhiều product series.
- Product series thuộc category.

## 4.1. Danh mục seed đề xuất

- iPhone.
- iPad.
- iPod.
- Phụ kiện sạc.

Dòng sản phẩm có thể gồm iPhone 15, iPhone 16, iPad, iPad Air, iPad Pro, iPod, USB-C Chargers và Charging Cables.

## 5. Bảng `products`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `category_id` | BIGINT UNSIGNED | FK |
| `product_series_id` | BIGINT UNSIGNED | FK, NULL |
| `name` | VARCHAR(160) | NOT NULL |
| `slug` | VARCHAR(180) | UNIQUE |
| `short_description` | VARCHAR(500) | NULL |
| `description` | LONGTEXT | NULL |
| `specifications` | LONGTEXT | NULL |
| `release_year` | SMALLINT UNSIGNED | NULL |
| `is_featured` | BOOLEAN | default false |
| `is_active` | BOOLEAN | default true |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | NULL |

Index:

- `category_id`
- `product_series_id`
- `release_year`
- `(is_active, is_featured)`
- `created_at`

## 6. Bảng `product_images`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `product_id` | BIGINT UNSIGNED | FK |
| `path` | VARCHAR(255) | NOT NULL |
| `alt_text` | VARCHAR(180) | NULL |
| `sort_order` | INT UNSIGNED | default 0 |
| `is_primary` | BOOLEAN | default false |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Quy tắc ứng dụng:

- Mỗi product có tối đa một ảnh primary.
- Nếu không có primary, dùng ảnh có `sort_order` thấp nhất.
- Xóa product phải xử lý file ảnh theo policy của dự án.

## 6.1. Quy ước lưu ảnh

Database lưu path tương đối, ví dụ:

    products/15/01JABCXYZ.webp

Không lưu domain hoặc URL đầy đủ.

Fallback placeholder không cần record trong `product_images`. Placeholder nằm tại:

    public/images/placeholders/product-placeholder.svg

Quy tắc ứng dụng:

- Ưu tiên ảnh `is_primary`.
- Nếu không có primary, dùng ảnh có `sort_order` thấp nhất.
- Nếu không có record ảnh, dùng placeholder.
- Seeder không được thất bại khi ảnh demo tùy chọn không tồn tại.

## 7. Bảng `colors`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `name` | VARCHAR(60) | NOT NULL |
| `slug` | VARCHAR(80) | UNIQUE |
| `hex_code` | CHAR(7) | NULL |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

`hex_code` chỉ dùng hiển thị, không phải nguồn xác định biến thể.

## 8. Bảng `storage_options`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `label` | VARCHAR(30) | NOT NULL |
| `capacity_gb` | INT UNSIGNED | UNIQUE |
| `is_active` | BOOLEAN | default true |
| `sort_order` | INT UNSIGNED | default 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Ví dụ:

| label | capacity_gb |
|---|---:|
| 64 GB | 64 |
| 128 GB | 128 |
| 256 GB | 256 |
| 512 GB | 512 |
| 1 TB | 1024 |

## 9. Bảng `product_variants`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `product_id` | BIGINT UNSIGNED | FK |
| `color_id` | BIGINT UNSIGNED | FK, NULL |
| `storage_option_id` | BIGINT UNSIGNED | FK, NULL |
| `sku` | VARCHAR(60) | UNIQUE |
| `original_price` | BIGINT UNSIGNED | NULL |
| `sale_price` | BIGINT UNSIGNED | NOT NULL |
| `stock_quantity` | INT UNSIGNED | default 0 |
| `is_active` | BOOLEAN | default true |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Không dùng composite unique bắt buộc cho color và storage vì phụ kiện có thể không có các thuộc tính này. SKU vẫn là unique key chính. Form Request hoặc service phải ngăn tạo tổ hợp variant trùng trong cùng product.

Index:

- `product_id`
- `color_id`
- `storage_option_id`
- `sale_price`
- `(is_active, stock_quantity)`

Quy tắc:

- `sale_price >= 0`
- `stock_quantity >= 0`
- Nếu có `original_price`, `sale_price <= original_price`
- SKU duy nhất.
- Không hard delete variant đã có order item.

## 9.1. Biến thể cho nhiều loại sản phẩm

- iPhone, iPad và iPod thường dùng color và storage.
- Phụ kiện sạc có thể chỉ dùng color hoặc không dùng cả hai.
- `color_id` và `storage_option_id` nullable.
- SKU bắt buộc và unique.
- Application validation ngăn tạo hai variant có cùng product, color và storage.
- Không dùng EAV trong MVP.

## 10. Bảng `orders`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `order_code` | VARCHAR(30) | UNIQUE |
| `user_id` | BIGINT UNSIGNED | FK |
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

Index:

- `user_id`
- `order_code`
- `status`
- `created_at`
- `(status, created_at)`

Quy tắc:

    total_amount = subtotal + shipping_fee

Client không được gửi giá trị tổng tiền đáng tin cậy.

## 11. Bảng `order_items`

| Cột | Kiểu đề xuất | Ràng buộc |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `order_id` | BIGINT UNSIGNED | FK |
| `product_id` | BIGINT UNSIGNED | FK, NULLABLE tùy policy |
| `product_variant_id` | BIGINT UNSIGNED | FK, NULLABLE tùy policy |
| `product_name` | VARCHAR(160) | NOT NULL |
| `sku` | VARCHAR(60) | NOT NULL |
| `color_name` | VARCHAR(60) | NOT NULL |
| `storage_label` | VARCHAR(30) | NOT NULL |
| `unit_price` | BIGINT UNSIGNED | NOT NULL |
| `quantity` | INT UNSIGNED | NOT NULL |
| `line_total` | BIGINT UNSIGNED | NOT NULL |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

Index:

- `order_id`
- `product_id`
- `product_variant_id`

Quy tắc:

    line_total = unit_price * quantity

Các cột snapshot không thay đổi khi product hoặc variant được sửa.

## 12. ERD dạng văn bản

    users 1 ----- n orders
    orders 1 ----- n order_items

    categories 1 ----- n product_series
    categories 1 ----- n products
    product_series 1 ----- n products

    products 1 ----- n product_images
    products 1 ----- n product_variants

    colors 1 ----- n product_variants
    storage_options 1 ----- n product_variants

    products 1 ----- n order_items
    product_variants 1 ----- n order_items

## 13. Chính sách khóa ngoại

Khuyến nghị:

- `product_series.category_id`: restrict delete.
- `products.category_id`: restrict delete.
- `products.product_series_id`: null on delete hoặc restrict.
- `product_images.product_id`: cascade delete khi product thật sự bị xóa.
- `product_variants.product_id`: restrict delete.
- `orders.user_id`: restrict delete.
- `order_items.order_id`: cascade delete chỉ khi order được xóa trong môi trường test; production không xóa order.
- Product và variant đã phát sinh order nên được deactivate hoặc soft delete.

## 14. Seeder tối thiểu

- 1 admin.
- 5 customer.
- 1 đến 2 category.
- 4 đến 5 product series.
- 8 đến 12 colors.
- 5 storage options.
- 10 đến 15 products.
- 30 đến 50 variants.
- 10 orders ở nhiều trạng thái.

## 15. Truy vấn product discovery

Query phải:

- Bắt đầu từ product active và chưa deleted.
- Eager load primary image.
- Dùng `whereHas` hoặc subquery cho variant filter.
- Dùng min price của active variants.
- Không join trực tiếp theo cách tạo duplicate product.
- Giữ pagination ở database.

Không:

- `Product::all()` rồi filter bằng collection.
- Query từng ảnh hoặc variant trong Blade.
- Cho client chọn raw column để order by.
