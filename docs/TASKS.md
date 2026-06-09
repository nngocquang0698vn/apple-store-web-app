# KẾ HOẠCH TRIỂN KHAI

## 1. Nguyên tắc

- Làm theo vertical slice.
- Mỗi phase phải chạy được.
- Không triển khai AJAX trước khi SSR hoạt động.
- Không làm phần `Could have` trước `Must have`.
- Mỗi task có test và commit riêng.
- Không yêu cầu Cursor làm toàn bộ website trong một prompt.

## Phase 0: Khởi tạo

### Task 0.1: Project bootstrap

- Tạo Laravel project.
- Cấu hình MySQL local.
- Cấu hình Vite.
- Cấu hình Tailwind.
- Cài jQuery.
- Cài Font Awesome Free qua npm và Vite.
- Tạo customer layout thân thiện và admin layout cơ bản.
- Thêm tài liệu Cursor.
- Tạo `.env.example`.
- Sao chép placeholder SVG cục bộ từ `starter-assets` vào `public/images/placeholders`.
- Không tải ảnh ngoài và không cài package xử lý ảnh.

Acceptance:

- App chạy.
- Kết nối database.
- Placeholder sản phẩm cục bộ tồn tại.
- Không có external image hotlink.
- Font Awesome icon hiển thị được ở vị trí như cart.
- `npm run build` thành công.
- Test mặc định chạy.

### Task 0.2: Coding foundation

- [x] Tạo enum role, user status và order status nếu chọn dùng enum.
- [x] Tạo middleware admin.
- [x] Tạo flash message component.
- [x] Thiết lập format code.
- [x] Xác định PHPUnit hoặc Pest.

## Phase 1: Authentication

### Task 1.1: Register

- Register routes.
- Register Form Request.
- Controller.
- Blade form.
- Hash password.
- Customer role mặc định.
- Tests.

### Task 1.2: Login và logout

- Login.
- Blocked account check.
- Session regeneration.
- POST logout.
- Tests.

### Task 1.3: Profile

- Edit profile.
- Change password.
- Ownership.
- Validation.
- Tests.

## Phase 2: Database catalog

### Task 2.1: Migrations

Tạo theo thứ tự:

1. users adjustments.
2. categories.
3. product_series.
4. products.
5. product_images.
6. colors.
7. storage_options.
8. product_variants.
9. orders.
10. order_items.

Acceptance:

- `migrate:fresh` thành công.
- Foreign keys đúng.
- Index đúng.

### Task 2.2: Models và factories

- Relationships.
- Casts.
- Factories.
- Seeders.
- Admin account.
- Demo catalog có iPhone, iPad, iPod và phụ kiện sạc.
- Seed data fallback được khi chưa có ảnh demo thật.

Acceptance:

- Seed chạy.
- Không N+1 trong test cơ bản.

## Phase 3: Admin catalog

### Task 3.1: Category và series

- CRUD.
- Slug.
- Active status.
- Validation.
- Tests.

### Task 3.2: Color và storage

- CRUD.
- Sort order.
- Validation.
- Tests.

### Task 3.3: Product

- CRUD.
- Soft delete hoặc deactivate.
- Image upload theo `docs/IMAGE_STRATEGY.md`.
- Relative path storage.
- Primary image và sort order.
- Validation.
- Tests.

### Task 3.4: Variant

- Create/update/deactivate.
- Unique SKU.
- Unique combination.
- Price rules.
- Stock rules.
- Tests.

Không làm dashboard trước khi catalog hoạt động.

## Phase 4: Product discovery SSR

### Task 4.1: Product listing

- Public route.
- Active products.
- Primary image.
- Minimum price.
- Pagination.
- Product card component.
- Money formatter hoặc Blade component cho VNĐ.
- Product image component và placeholder fallback.
- Eager load ảnh hiển thị để tránh N+1.
- Tests.

### Task 4.2: Search

- `q`.
- Product name.
- Series name.
- Short description.
- SKU.
- Tests.

### Task 4.3: Filters

- Category.
- Series.
- Colors.
- Storages.
- Price.
- In stock.
- Featured.
- Tests kết hợp.

### Task 4.4: Sort

- Newest.
- Price asc.
- Price desc.
- Name asc.
- Name desc.
- Whitelist.
- Tests.

### Task 4.5: Filter UI

- GET form.
- Desktop sidebar.
- Mobile drawer.
- Applied filter summary.
- Clear filter.
- Empty state.
- Pagination preserves query.

Acceptance cho Phase 4:

- Hoạt động khi JavaScript tắt.
- URL chia sẻ được.
- Không duplicate product.
- Không N+1.
- Test combinations pass.

## Phase 5: Product detail và cart SSR

### Task 5.1: Product detail

- Gallery.
- Variant data.
- Color và storage selectors.
- Stock state.
- Add cart form.
- Tests.

### Task 5.2: Session cart

- CartService.
- Add.
- Update.
- Remove.
- Clear.
- Server recalculation.
- Tests.

Acceptance:

- Không vượt stock.
- Không tin giá client.
- Disabled variant bị từ chối.

## Phase 6: jQuery enhancement

Chỉ bắt đầu sau khi Phase 4 và 5 ổn định.

### Task 6.1: Variant selector

- Update price động.
- Update stock động.
- Update ảnh variant khi có.
- Disable invalid combination.
- Server vẫn validate.
- SSR fallback giữ nguyên.

### Task 6.2: AJAX cart

- Add, update và remove.
- CSRF.
- Header cart count.
- Loading state.
- Server-returned unit price.
- Server-returned line subtotal.
- Server-returned cart subtotal.
- Server-returned shipping fee và grand total.
- Price và stock conflict handling.
- Error handling.
- Non-AJAX fallback.

### Task 6.3: Filter enhancement

- Optional auto-submit.
- Debounce search.
- Loading.
- Partial result.
- History API.
- Back/forward support.
- SSR fallback.

Nếu task này tốn quá nhiều thời gian, giữ filter SSR và bỏ AJAX filter.

### Task 6.4: Dynamic checkout summary

- Reuse CartService và pricing logic.
- Thêm summary endpoint nếu thật sự cần.
- Return integer VNĐ totals.
- Refresh summary bằng jQuery.
- Handle price và stock changes.
- Không tin DOM hoặc hidden totals.
- Checkout POST vẫn là authoritative operation.

## Phase 7: Checkout

### Task 7.1: Orders schema and model verification

- Order relationships.
- Order code generator.
- Snapshot fields.
- Status cast.

### Task 7.2: Checkout form

- Auth middleware.
- Address fields.
- COD.
- Summary.
- Validation.

### Task 7.3: PlaceOrder action

- Transaction.
- `lockForUpdate`.
- Recheck.
- Create order.
- Create items.
- Decrease stock.
- Clear cart after commit.
- Tests.

Acceptance:

- Race-sensitive logic có lock.
- Rollback khi thiếu stock.
- Không partial order.
- Tổng tiền đúng.

## Phase 8: Customer orders

### Task 8.1: Order history

- Current user only.
- Pagination.
- Status badge.
- Tests.

### Task 8.2: Order detail

- Policy.
- Snapshot display.
- 403 hoặc 404 khi không sở hữu.
- Tests.

## Phase 9: Admin orders và customers

### Task 9.1: Order list

- Search code.
- Status filter.
- Date range.
- Pagination.

### Task 9.2: Order detail

- Customer snapshot.
- Items.
- Totals.
- Status.

### Task 9.3: State transitions

- Valid transitions.
- Separate cancel action.
- Restock once.
- Tests.

### Task 9.4: Customers

- List.
- Search.
- Block/unblock.
- Prevent unsafe self-block if needed.
- Tests.

## Phase 10: Dashboard

- Totals.
- Pending orders.
- Completed revenue.
- Latest orders.
- Low-stock variants.

Không cần chart nếu thiếu thời gian.

## Phase 11: Hoàn thiện

- Responsive review.
- Accessibility basics.
- Security review.
- Query review.
- Image validation.
- Kiểm tra placeholder và fallback.
- Kiểm tra không có external image hotlink.
- Empty states.
- Vietnamese text.
- README chạy project.
- Demo seed.
- Final test.
- Production config checklist.

## Test checklist cuối

### Authentication

- Duplicate email.
- Duplicate phone.
- Wrong password.
- Blocked account.
- Session regeneration.
- Customer cannot access admin.

### Product discovery

- Single filter.
- Combined filters.
- Search SKU.
- Price sort.
- Invalid sort.
- Pagination query preservation.
- Empty result.
- Active-only visibility.

### Cart

- Add.
- Add same variant.
- Update.
- Remove.
- Exceed stock.
- Disabled variant.
- Client price ignored.

### Checkout

- Guest rejected.
- Empty cart rejected.
- Stock changed before checkout.
- Price changed before checkout.
- Successful order.
- Transaction rollback.
- Stock decreased.

### Orders

- Ownership.
- Valid status transition.
- Invalid transition.
- Cancellation restock.
- Cancellation not duplicated.

## Git branches gợi ý

- `feature/authentication`
- `feature/catalog-admin`
- `feature/product-discovery`
- `feature/session-cart`
- `feature/checkout`
- `feature/order-management`
- `chore/final-hardening`

## Commit gợi ý

- `feat: add product filter request`
- `feat: implement product query`
- `test: cover combined product filters`
- `feat: add session cart`
- `feat: place order in database transaction`
- `fix: prevent duplicate inventory restock`

## Frontend dependency rule

- jQuery là mặc định.
- Font Awesome Free đã được phê duyệt.
- Mọi thư viện JavaScript khác chỉ được proposal.
- Không cài hoặc sửa lock file trước khi human xác nhận.

## UX checklist cuối

- Search, account và cart dễ thấy.
- Navigation có iPhone, iPad, iPod và phụ kiện sạc.
- Icon có accessible label khi cần.
- Giá hiển thị VNĐ nhất quán.
- Cart và checkout cập nhật động.
- Mobile flow mua hàng sử dụng được.
