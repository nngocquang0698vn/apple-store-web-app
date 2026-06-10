# Checklist tiến độ dự án

Cập nhật theo `docs/TASKS.md` và trạng thái code thực tế (tháng 6/2026).

**Ký hiệu**

| Ký hiệu | Ý nghĩa |
|--------|---------|
| ✅ | Hoàn thành |
| ⏸️ | Làm một phần / có tech debt |
| ⏭️ | Cố ý bỏ qua (skip) |
| ❌ | Chưa làm |

**Tests hiện tại:** `php artisan test` → **170 passed**

---

## Tóm tắt nhanh

| Phase | Tên | Trạng thái |
|-------|-----|------------|
| 0 | Khởi tạo | ✅ |
| 1 | Authentication | ✅ |
| 2 | Database catalog | ✅ |
| **3** | **Admin catalog** | **✅** |
| 4 | Product discovery (SSR) | ✅ |
| 5 | Product detail + cart (SSR) | ✅ |
| 6 | jQuery enhancement | ⏸️ |
| 7 | Checkout | ✅ |
| 8 | Customer orders | ✅ |
| 9 | Admin orders & customers | ✅ |
| 10 | Dashboard | ✅ |
| 11 | Hoàn thiện | ✅ |

**Phase 3 đã hoàn thành:** CRUD danh mục, dòng sản phẩm, màu, dung lượng, sản phẩm, upload ảnh và biến thể.

**Phase 0–11 đã hoàn thành.** Tech debt UX (TD-001/002) có thể làm sau nếu cần.

---

## Phase 0: Khởi tạo

| Task | Mô tả | Trạng thái |
|------|-------|------------|
| 0.1 | Project bootstrap (Laravel, Vite, Tailwind, jQuery, FA, layout, placeholder) | ✅ |
| 0.2 | Coding foundation (enum, admin middleware, flash, Pint, PHPUnit) | ✅ |

---

## Phase 1: Authentication

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 1.1 | Register | ✅ | `RegisterTest` |
| 1.2 | Login, logout, blocked account, session | ✅ | `LoginTest`, `LogoutTest` |
| 1.3 | Profile, đổi mật khẩu | ✅ | `ProfileTest` |

---

## Phase 2: Database catalog

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 2.1 | Migrations (users → order_items) | ✅ | `CatalogMigrationTest` |
| 2.2 | Models, factories, seeders, demo catalog | ✅ | `CatalogSeederTest`, `CatalogRelationshipTest` |

---

## Phase 3: Admin catalog ✅

> Admin catalog đã có route/controller/request/view/test theo luồng SSR.

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 3.1 | Category & series CRUD | ✅ | `AdminCategorySeriesTest` |
| 3.2 | Color & storage CRUD | ✅ | `AdminColorStorageTest` |
| 3.3 | Product CRUD + upload ảnh | ✅ | `AdminProductTest` |
| 3.4 | Variant CRUD (SKU, giá, tồn kho) | ✅ | `AdminProductVariantTest` |

**Đã có:** `admin/categories`, `admin/product-series`, `admin/colors`, `admin/storage-options`, `admin/products`, `admin/products/{product}/variants`.

---

## Phase 4: Product discovery SSR

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 4.1 | Listing, pagination, product card, money, image | ✅ | `ProductDiscoveryTest` |
| 4.2 | Search (`q`, name, series, SKU) | ✅ | `ProductDiscoveryTest` |
| 4.3 | Filters (category, series, colors, …) | ✅ | `ProductDiscoveryTest` |
| 4.4 | Sort whitelist | ✅ | `ProductDiscoveryTest` |
| 4.5 | Filter UI (sidebar, mobile, empty state) | ✅ | Blade + tests |

---

## Phase 5: Product detail & cart SSR

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 5.1 | Product detail, variant selector, add cart | ✅ | `ProductShowTest` |
| 5.2 | CartService (add/update/remove/clear) | ✅ | `CartTest` |

---

## Phase 6: jQuery enhancement

| Task | Mô tả | Trạng thái | Ghi chú |
|------|-------|------------|---------|
| 6.1 | Variant selector động (giá, stock, ảnh) | ✅ | `product-variant.js` |
| 6.2 | AJAX cart | ⏸️ | `cart.js`, `CartAjaxTest` — **rollback auto-sync số lượng** → xem `docs/TECHDEBT.md` TD-001 |
| 6.3 | Filter AJAX (partial, history) | ✅ | `product-filters.js`, `ProductFilterAjaxTest` |
| 6.4 | Dynamic checkout summary | ⏸️ | `checkout.js`, `CheckoutSummaryTest` — nút **Cập nhật tổng tiền** thủ công → TD-002 |

---

## Phase 7: Checkout

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 7.1 | Order model, code generator, snapshot | ✅ | `OrderCodeGeneratorTest` |
| 7.2 | Checkout form, COD, validation | ✅ | `CheckoutTest` |
| 7.3 | PlaceOrder (transaction, lock, stock) | ✅ | `CheckoutTest` |

---

## Phase 8: Customer orders

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 8.1 | Lịch sử đơn, pagination, status badge | ✅ | `CustomerOrderTest` |
| 8.2 | Chi tiết đơn, policy, snapshot | ✅ | `CustomerOrderTest` |

---

## Phase 9: Admin orders & customers ✅

| Task | Mô tả | Trạng thái | Tests |
|------|-------|------------|-------|
| 9.1 | Danh sách đơn (search, filter, date, pagination) | ✅ | `AdminOrderTest` |
| 9.2 | Chi tiết đơn admin | ✅ | `AdminOrderTest` |
| 9.3 | Đổi trạng thái, hủy đơn, hoàn tồn kho | ✅ | `AdminOrderTest`, `OrderStatusTest` |
| 9.4 | Quản lý khách (list, search, block/unblock) | ✅ | `AdminCustomerTest` |

---

## Phase 10: Dashboard ✅

| Hạng mục | Trạng thái | Tests |
|----------|------------|-------|
| Tổng sản phẩm / khách / đơn chờ / doanh thu | ✅ | `AdminDashboardStatsTest` |
| Đơn mới nhất | ✅ | `AdminDashboardStatsTest` |
| Variant sắp hết hàng (`LOW_STOCK_THRESHOLD`) | ✅ | `AdminDashboardStatsTest` |

---

## Phase 11: Hoàn thiện ✅

| Hạng mục | Trạng thái |
|----------|------------|
| Responsive / a11y (skip link, `main-content`) | ✅ |
| Security review (session regen, admin middleware, upload) | ✅ |
| Image validation | ✅ (`ProductImageStoreRequest`, `AdminProductTest`) |
| Placeholder & no hotlink | ✅ (`ProductImageUrl`, `ApplicationHardeningTest`) |
| Empty states toàn site | ✅ (products, cart, orders, admin lists) |
| Vietnamese text | ✅ |
| README cập nhật | ✅ |
| Demo seed ổn định | ✅ |
| Final test suite | ✅ |
| Production config checklist | ✅ [`docs/PRODUCTION_CHECKLIST.md`](PRODUCTION_CHECKLIST.md) |
| Danh mục nhanh trên navbar | ✅ (`navCategories` trong `layouts/app`) |

---

## Tech debt (không phải task TASKS.md nhưng cần nhớ)

| ID | Mô tả | File |
|----|-------|------|
| TD-001 | Auto cập nhật số lượng giỏ (đã rollback) | `docs/TECHDEBT.md` |
| TD-002 | Auto checkout summary (đã rollback) | `docs/TECHDEBT.md` |
| TD-003 | Fix `data-product-add-button` | ✅ Đã fix |
| TD-004 | JSON 422 kèm `data` khi cart fail | ⏸️ Một phần |

---

## Test checklist cuối (`TASKS.md` § cuối)

### Authentication

| Case | Trạng thái |
|------|------------|
| Duplicate email / phone | ✅ |
| Wrong password | ✅ |
| Blocked account | ✅ |
| Session regeneration | ✅ |
| Customer cannot access admin | ✅ |

### Product discovery

| Case | Trạng thái |
|------|------------|
| Single / combined filters | ✅ |
| Search SKU | ✅ |
| Price sort / invalid sort | ✅ |
| Pagination preserves query | ✅ |
| Empty result / active-only | ✅ |

### Cart

| Case | Trạng thái |
|------|------------|
| Add / same variant / update / remove | ✅ |
| Exceed stock / disabled variant | ✅ |
| Client price ignored | ✅ |

### Checkout

| Case | Trạng thái |
|------|------------|
| Guest / empty cart rejected | ✅ |
| Stock & price changed before checkout | ✅ |
| Successful order / stock decreased | ✅ |
| Transaction rollback | ⏸️ (chưa có test tên rõ, logic có trong `PlaceOrder`) |

### Orders

| Case | Trạng thái |
|------|------------|
| Ownership (customer) | ✅ |
| Valid / invalid status transition | ✅ |
| Cancellation restock / no duplicate restock | ✅ |

---

## Thứ tự làm tiếp (gợi ý)

1. **Tech debt** — TD-001/002 (auto cart sync, checkout summary) nếu muốn cải thiện UX.
2. **Deploy / demo** — theo [`docs/PRODUCTION_CHECKLIST.md`](PRODUCTION_CHECKLIST.md).

---

## Liên quan

- Kế hoạch chi tiết: [`docs/TASKS.md`](TASKS.md)
- Việc hoãn / rollback UX: [`docs/TECHDEBT.md`](TECHDEBT.md)
- Route contract: [`docs/ROUTES.md`](ROUTES.md)
- Production: [`docs/PRODUCTION_CHECKLIST.md`](PRODUCTION_CHECKLIST.md)
