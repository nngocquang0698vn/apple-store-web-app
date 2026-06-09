# ROUTES VÀ HTTP CONTRACT

## 1. Quy ước

- Route public dùng tên rõ ràng.
- Resource quan trọng dùng route model binding.
- Product public bind bằng slug.
- Order account bind bằng order code nhưng vẫn phải kiểm tra ownership.
- Request thay đổi dữ liệu dùng POST, PATCH hoặc DELETE.
- Logout dùng POST.
- SSR là mặc định.
- AJAX dùng cùng nghiệp vụ backend.

## 2. Public routes

| Method | URI | Name | Chức năng |
|---|---|---|---|
| GET | `/` | `home` | Trang chủ |
| GET | `/products` | `products.index` | Search, filter, sort, pagination |
| GET | `/products/{product:slug}` | `products.show` | Chi tiết sản phẩm |

### `GET /products`

Query parameters:

- `q`
- `category`
- `series`
- `colors[]`
- `storages[]`
- `min_price`
- `max_price`
- `in_stock`
- `featured`
- `sort`
- `page`

Response:

- HTML đầy đủ cho request thường.
- Có thể trả partial HTML khi request AJAX có contract rõ ràng.

## 3. Guest authentication routes

| Method | URI | Name |
|---|---|---|
| GET | `/register` | `register` |
| POST | `/register` | `register.store` |
| GET | `/login` | `login` |
| POST | `/login` | `login.store` |

Middleware:

- `guest`

## 4. Authenticated routes

| Method | URI | Name |
|---|---|---|
| POST | `/logout` | `logout` |
| GET | `/account/profile` | `account.profile.edit` |
| PATCH | `/account/profile` | `account.profile.update` |
| PATCH | `/account/password` | `account.password.update` |
| GET | `/account/orders` | `account.orders.index` |
| GET | `/account/orders/{order:order_code}` | `account.orders.show` |

Middleware:

- `auth`
- Account active check khi cần.

## 5. Cart routes

Cart có thể dùng khi chưa đăng nhập.

| Method | URI | Name | Chức năng |
|---|---|---|---|
| GET | `/cart` | `cart.index` | Xem giỏ |
| POST | `/cart/items` | `cart.items.store` | Thêm variant |
| PATCH | `/cart/items/{variant}` | `cart.items.update` | Cập nhật số lượng |
| DELETE | `/cart/items/{variant}` | `cart.items.destroy` | Xóa dòng |
| DELETE | `/cart` | `cart.destroy` | Xóa toàn bộ |

### Add cart request

    {
        "variant_id": 15,
        "quantity": 2
    }

Server phải bỏ qua mọi field giá hoặc product name gửi kèm.

### AJAX success response

    {
        "success": true,
        "message": "Đã thêm sản phẩm vào giỏ hàng.",
        "data": {
            "cart_count": 2,
            "cart_subtotal": 39980000
        }
    }

### AJAX validation response

HTTP 422 với error bag chuẩn của Laravel.

### Non-AJAX fallback

Redirect back với flash message.

## 6. Checkout routes

| Method | URI | Name |
|---|---|---|
| GET | `/checkout` | `checkout.create` |
| POST | `/checkout` | `checkout.store` |
| GET | `/checkout/success/{order:order_code}` | `checkout.success` |

Middleware:

- `auth`

### Checkout request

    {
        "receiver_name": "Nguyễn Văn A",
        "receiver_phone": "0900000000",
        "province": "TP. Hồ Chí Minh",
        "district": "Thành phố Thủ Đức",
        "ward": "Phường Linh Trung",
        "address_line": "Số 1 đường ABC",
        "note": "Giao giờ hành chính"
    }

Không nhận:

- subtotal.
- shipping_fee từ client.
- total_amount.
- status.
- payment status.
- unit price.

## 7. Admin routes

Prefix:

    /admin

Middleware:

- `auth`
- `admin`

### Dashboard

| Method | URI | Name |
|---|---|---|
| GET | `/admin` | `admin.dashboard` |

### Categories

| Method | URI | Name |
|---|---|---|
| GET | `/admin/categories` | `admin.categories.index` |
| GET | `/admin/categories/create` | `admin.categories.create` |
| POST | `/admin/categories` | `admin.categories.store` |
| GET | `/admin/categories/{category}/edit` | `admin.categories.edit` |
| PATCH | `/admin/categories/{category}` | `admin.categories.update` |
| DELETE | `/admin/categories/{category}` | `admin.categories.destroy` |

### Product series

Dùng resource routes tương tự:

    Route::resource('product-series', Admin\ProductSeriesController::class)
        ->except(['show']);

### Colors

    Route::resource('colors', Admin\ColorController::class)
        ->except(['show']);

### Storage options

    Route::resource('storage-options', Admin\StorageOptionController::class)
        ->except(['show']);

### Products

| Method | URI | Name |
|---|---|---|
| GET | `/admin/products` | `admin.products.index` |
| GET | `/admin/products/create` | `admin.products.create` |
| POST | `/admin/products` | `admin.products.store` |
| GET | `/admin/products/{product}` | `admin.products.show` |
| GET | `/admin/products/{product}/edit` | `admin.products.edit` |
| PATCH | `/admin/products/{product}` | `admin.products.update` |
| DELETE | `/admin/products/{product}` | `admin.products.destroy` |

### Product images

| Method | URI | Name |
|---|---|---|
| POST | `/admin/products/{product}/images` | `admin.products.images.store` |
| PATCH | `/admin/product-images/{image}` | `admin.product-images.update` |
| DELETE | `/admin/product-images/{image}` | `admin.product-images.destroy` |

### Variants

| Method | URI | Name |
|---|---|---|
| GET | `/admin/products/{product}/variants` | `admin.products.variants.index` |
| POST | `/admin/products/{product}/variants` | `admin.products.variants.store` |
| PATCH | `/admin/variants/{variant}` | `admin.variants.update` |
| DELETE | `/admin/variants/{variant}` | `admin.variants.destroy` |

### Orders

| Method | URI | Name |
|---|---|---|
| GET | `/admin/orders` | `admin.orders.index` |
| GET | `/admin/orders/{order}` | `admin.orders.show` |
| PATCH | `/admin/orders/{order}/status` | `admin.orders.status.update` |
| POST | `/admin/orders/{order}/cancel` | `admin.orders.cancel` |

Cancellation dùng endpoint riêng vì có nghiệp vụ hoàn tồn kho.

### Customers

| Method | URI | Name |
|---|---|---|
| GET | `/admin/customers` | `admin.customers.index` |
| GET | `/admin/customers/{user}` | `admin.customers.show` |
| PATCH | `/admin/customers/{user}/status` | `admin.customers.status.update` |

## 8. Filter validation

`ProductFilterRequest` nên chuẩn hóa:

- `q`: trim, max 100.
- `category`: slug tồn tại và active hoặc nullable.
- `series`: slug tồn tại và active hoặc nullable.
- `colors`: array unique, mỗi phần tử là slug hợp lệ.
- `storages`: array unique, mỗi phần tử integer hợp lệ.
- `min_price`: integer, min 0.
- `max_price`: integer, min 0, lớn hơn hoặc bằng min.
- `in_stock`: boolean.
- `featured`: boolean.
- `sort`: in whitelist.
- `page`: integer, min 1.

Chọn một chính sách:

- Invalid filter trả validation error.
- Hoặc invalid optional filter bị bỏ qua.

Khuyến nghị:

- Field có type sai trả validation error.
- Slug không tồn tại được bỏ qua hoặc trả empty result nhất quán.
- Sort không hợp lệ quay về default.

## 9. Route authorization

- Admin middleware cho mọi `/admin/*`.
- Order account dùng Policy.
- Product public chỉ bind active product bằng custom scoped query hoặc kiểm tra trong controller.
- User status blocked bị chặn khỏi authenticated business flow.
- Không tin việc ẩn link trên Blade là authorization.

## 10. Named route requirement

Blade và controller phải dùng named routes.

Không hard-code URL khi đã có route name.

Ví dụ:

    route('products.show', $product)
    route('cart.items.store')
    route('admin.orders.show', $order)
