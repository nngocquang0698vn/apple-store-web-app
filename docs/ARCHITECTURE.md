# KIẾN TRÚC HỆ THỐNG

## 1. Mục tiêu kiến trúc

Kiến trúc phải:

- Đủ rõ để trình bày trong báo cáo.
- Dễ triển khai bằng Cursor theo từng feature.
- Không tạo quá nhiều tầng không cần thiết.
- Tách nghiệp vụ quan trọng khỏi controller.
- Hỗ trợ test.
- Giữ server-side rendering là luồng chính.

## 2. Kiến trúc tổng thể

Hệ thống là Laravel monolith.

    Browser
       |
       | HTTP GET/POST/PATCH/DELETE
       v
    Laravel Routes
       |
       v
    Middleware
       |
       v
    Form Request
       |
       v
    Controller
       |
       +--> Query Object for read-heavy filtering
       |
       +--> Action or Service for multi-step writes
       |
       v
    Eloquent Models
       |
       v
    MySQL
       |
       v
    Blade View or Redirect

Không xây frontend app riêng.

## 3. Module

### 3.1. Identity

Phụ trách:

- Register.
- Login.
- Logout.
- Profile.
- Password change.
- Role và account status.

### 3.2. Catalog

Phụ trách:

- Category.
- Product series.
- Color.
- Storage option.
- Product.
- Product image.
- Product variant.
- Inventory fields.

### 3.3. Product Discovery

Phụ trách:

- Search.
- Filter.
- Sort.
- Pagination.
- Query string.
- Product card price aggregation.

Class chính đề xuất:

    app/Queries/ProductQuery.php

Hoặc:

    app/Services/ProductSearchService.php

Chỉ chọn một cách. Khuyến nghị `ProductQuery`.

### 3.4. Cart

Phụ trách:

- Session cart.
- Add.
- Update.
- Remove.
- Clear.
- Totals.
- Validation against current catalog.

Class đề xuất:

    app/Services/CartService.php

### 3.5. Ordering

Phụ trách:

- Checkout.
- Order creation.
- Order item snapshot.
- Inventory decrease.
- Cancellation and restock.
- State transitions.

Classes đề xuất:

    app/Actions/Orders/PlaceOrder.php
    app/Actions/Orders/ChangeOrderStatus.php
    app/Actions/Orders/CancelOrder.php

Không cần vừa Service vừa Action cho cùng nghiệp vụ.

### 3.6. Administration

Phụ trách:

- Dashboard.
- Catalog CRUD.
- Order processing.
- Customer status.

## 4. Tổ chức thư mục

    app/
    ├── Actions/
    │   └── Orders/
    ├── Enums/
    ├── Http/
    │   ├── Controllers/
    │   │   ├── Admin/
    │   │   └── Account/
    │   ├── Middleware/
    │   └── Requests/
    │       ├── Admin/
    │       ├── Auth/
    │       ├── Cart/
    │       └── Checkout/
    ├── Models/
    ├── Policies/
    ├── Queries/
    ├── Services/
    └── View/
        └── Components/

    resources/
    ├── css/
    ├── js/
    │   ├── cart.js
    │   ├── product-filters.js
    │   ├── product-variant.js
    │   └── admin.js
    └── views/
        ├── account/
        ├── admin/
        ├── auth/
        ├── cart/
        ├── checkout/
        ├── components/
        ├── layouts/
        ├── orders/
        └── products/

    tests/
    ├── Feature/
    └── Unit/

Không bắt buộc tạo tất cả thư mục từ đầu. Chỉ tạo khi feature cần.

## 5. Controller

Controller nên:

- Nhận Form Request.
- Gọi query, action hoặc service.
- Trả view, redirect hoặc JSON nhỏ.
- Không chứa transaction dài.
- Không chứa truy vấn filter phức tạp.
- Không tự tính tổng tiền từ request.
- Không chứa HTML.

Ví dụ trách nhiệm hợp lệ:

    public function index(ProductFilterRequest $request, ProductQuery $products)
    {
        return view('products.index', [
            'products' => $products->paginate($request->filters()),
        ]);
    }

Ví dụ không hợp lệ:

- Controller 300 dòng.
- Tự nối từng `where` trong nhiều controller.
- Tạo order và trừ stock trực tiếp mà không transaction.
- Dùng `$request->all()` để ghi model.

## 6. Form Request

Dùng cho:

- Register.
- Login khi validation đáng kể.
- Product filters.
- Add/update cart.
- Checkout.
- Admin product.
- Admin variant.
- Order status update.
- Profile update.

Form Request phải:

- Chuẩn hóa kiểu dữ liệu khi cần.
- Whitelist sort.
- Giới hạn độ dài `q`.
- Xác minh ID hoặc slug hợp lệ.
- Trả message tiếng Việt khi phù hợp.
- Chỉ expose dữ liệu qua `validated()` hoặc method `filters()`.

## 7. Query Object cho sản phẩm

`ProductQuery` là nguồn duy nhất của logic tìm kiếm công khai.

Trách nhiệm:

- Chỉ lấy product active.
- Chỉ xét variant active.
- Eager load ảnh chính và dữ liệu card.
- Search.
- Filter.
- Sort.
- Tính min price.
- Paginate.
- Giữ query string.

Không làm:

- Render HTML.
- Đọc session cart.
- Ghi database.
- Chứa logic admin CRUD.

Gợi ý interface:

    final class ProductQuery
    {
        public function paginate(ProductFilters $filters): LengthAwarePaginator;
    }

Có thể dùng array thay DTO để giảm scope:

    public function paginate(array $filters): LengthAwarePaginator;

Không bắt buộc tạo DTO nếu nó làm dự án phức tạp hơn.

## 8. CartService

API đề xuất:

    getItems(): Collection
    add(int $variantId, int $quantity): void
    update(int $variantId, int $quantity): void
    remove(int $variantId): void
    clear(): void
    count(): int
    subtotal(): int

Lưu tiền bằng integer VND nếu dự án chỉ dùng VND.

Không dùng float cho tiền.

## 9. Order actions

### 9.1. PlaceOrder

Input:

- Authenticated user.
- Validated shipping data.
- Session cart.

Output:

- Order đã tạo.

Bắt buộc:

- Transaction.
- `lockForUpdate`.
- Recalculate.
- Snapshot.
- Stock decrease.
- Clear cart after commit.

### 9.2. ChangeOrderStatus

- Validate transition.
- Không nhận mọi status tùy ý.
- Không cho skip các bước chính.
- Không xử lý cancellation như update status thông thường nếu cancellation cần restock.

### 9.3. CancelOrder

- Transaction.
- Lock order và variants.
- Chỉ pending hoặc confirmed.
- Restock.
- Mark cancelled.
- Idempotent guard.

## 10. Eloquent

Quy tắc:

- Định nghĩa relationships.
- Dùng `$fillable` cẩn thận hoặc `$guarded` theo convention đã chọn.
- Dùng casts cho boolean, date và enum.
- Eager load ở list page.
- Tránh accessor thực hiện query.
- Không tạo hidden query trong Blade.
- Dùng soft deletes cho product nếu cần giữ lịch sử.

## 11. Enum

Có thể dùng backed enum cho:

    UserRole
    UserStatus
    OrderStatus

Ví dụ:

    enum OrderStatus: string
    {
        case Pending = 'pending';
        case Confirmed = 'confirmed';
        case Shipping = 'shipping';
        case Completed = 'completed';
        case Cancelled = 'cancelled';
    }

Không bắt buộc enum cho mọi field.

## 12. Blade

Sử dụng:

- Layout khách hàng.
- Layout admin.
- Blade components cho button, input, alert, badge, product card, pagination wrapper và empty state.
- Partial riêng cho product result nếu dùng AJAX.

Không:

- Query database trong Blade.
- Tính nghiệp vụ trong Blade.
- Dùng raw output cho input người dùng.
- Lặp lại markup lớn ở nhiều trang.

## 12.1. Image presentation

Luồng hình ảnh:

    ProductQuery eager loads display image data
    -> Controller passes product data
    -> Blade image component renders URL
    -> Local placeholder is used when no image exists

Không:

- Query `images()` trong Blade.
- Tạo accessor phát sinh query ngầm cho từng product.
- Dùng URL ngoài làm fallback.
- Cài package xử lý ảnh trong MVP chỉ để tạo thumbnail.

Có thể tạo Blade component:

    resources/views/components/product-image.blade.php

Component nhận:

- URL đã resolve.
- Alt text.
- Kích thước hoặc variant hiển thị.
- Lazy loading flag.

Logic chọn primary image phải nằm ở query/model relationship/service phù hợp, không nằm trong component.

## 13. jQuery

Các module hợp lệ:

- `product-variant.js`: chọn variant.
- `cart.js`: add/update/remove cart.
- `product-filters.js`: progressive enhancement.
- `admin.js`: confirm delete/status action.

Quy tắc:

- Không inline handlers như `onclick`.
- Bind bằng data attributes.
- CSRF lấy từ meta tag.
- Xử lý success và error.
- Có fallback SSR.
- Không lưu giá tin cậy ở JavaScript.
- Không tự quyết định inventory.

## 14. Response

### SSR request

- Trả Blade view hoặc redirect.
- Validation lỗi quay lại form với old input.
- Success dùng flash message.

### AJAX request

Trả JSON nhỏ:

    {
        "success": true,
        "message": "Đã cập nhật giỏ hàng.",
        "data": {
            "cart_count": 3,
            "cart_subtotal": 39980000
        }
    }

Hoặc trả partial HTML cho vùng kết quả sản phẩm.

Không xây API layer đầy đủ cho MVP.

## 15. Error handling

- 404 cho resource không public.
- 403 cho thiếu quyền.
- 422 cho AJAX validation.
- Flash error cho SSR.
- Không lộ exception trong production.
- Log lỗi server nhưng không log password hoặc secret.

## 16. Testing strategy

### Feature tests

Ưu tiên:

- Authentication.
- Product discovery.
- Cart.
- Checkout.
- Order ownership.
- Admin authorization.
- Order transitions.
- Inventory.

### Unit tests

Chỉ thêm khi logic độc lập có lợi:

- Order transition rule.
- Money formatting helper.
- Filter normalization.

Không viết unit test chỉ để tăng số lượng.

## 17. Quy tắc thay đổi kiến trúc

Agent không được:

- Tự thêm repository pattern.
- Tự chuyển sang SPA.
- Tự thêm event sourcing.
- Tự thêm queue.
- Tự thêm cache bắt buộc.
- Tự thêm search engine.
- Tự thêm package admin panel.

Mọi thay đổi lớn phải:

1. Nêu vấn đề hiện tại.
2. Đưa phương án đơn giản nhất.
3. Nêu file và migration ảnh hưởng.
4. Được người dùng chấp thuận trước.
