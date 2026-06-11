# EXTRA FEATURE SPEC — ENHANCED ADD-TO-CART UX

## 1. Mục tiêu

Tính năng này cải thiện trải nghiệm thêm sản phẩm vào giỏ hàng và cập nhật số lượng sản phẩm trong giỏ bằng jQuery, giúp website thân thiện và giống một website thương mại điện tử thật hơn.

Mục tiêu chính:

- Người dùng thấy giá thay đổi ngay khi tăng hoặc giảm số lượng.
- Không cần reload toàn bộ trang khi thêm, sửa hoặc xóa sản phẩm trong giỏ.
- Cart badge trên header cập nhật ngay.
- Tổng tiền trong giỏ và checkout summary cập nhật động.
- Có loading state, error state và thông báo rõ ràng.
- Dùng jQuery, không thêm framework JavaScript.
- Server vẫn là nguồn dữ liệu tin cậy cho giá, tồn kho và tổng tiền.

---

# 2. Phạm vi

## 2.1. Bao gồm

Áp dụng cho:

- Product detail.
- Mini cart hoặc cart badge trên header.
- Cart page.
- Checkout summary, nếu checkout page đã có.
- AJAX add-to-cart.
- AJAX update quantity.
- AJAX remove item.
- Debounce khi người dùng nhập số lượng.
- Tự cập nhật line subtotal.
- Tự cập nhật cart subtotal.
- Tự cập nhật shipping fee, nếu có.
- Tự cập nhật grand total.
- Xử lý thay đổi tồn kho.
- Xử lý thay đổi giá.

## 2.2. Không bao gồm

Không triển khai trong phase này:

- Coupon.
- Online payment.
- Guest checkout phức tạp.
- Persistent cart theo database.
- Real-time multi-tab sync.
- WebSocket.
- React, Vue, Alpine, Livewire hoặc Inertia.
- Animation phức tạp.
- Cart drawer phức tạp nếu chưa có UI nền tảng.

---

# 3. Nguyên tắc quan trọng

## 3.1. Server là nguồn dữ liệu tin cậy

jQuery có thể cập nhật giao diện nhanh, nhưng không được là nguồn nghiệp vụ.

Không tin:

- Giá trong DOM.
- Giá trong hidden input.
- Tổng tiền do JavaScript tự tính.
- Tồn kho lưu trong data attribute.
- Quantity bị sửa bằng devtools.
- Product variant ID không được kiểm tra.

Server phải tính lại:

- Unit price.
- Line subtotal.
- Cart subtotal.
- Shipping fee.
- Grand total.
- Stock state.
- Cart count.

## 3.2. JavaScript chỉ làm UI

jQuery được phép:

- Disable button khi đang gửi request.
- Hiển thị spinner.
- Tăng giảm số lượng trên UI.
- Debounce request.
- Format số tiền VNĐ để hiển thị.
- Cập nhật DOM từ response của server.
- Show toast hoặc flash message.

jQuery không được:

- Quyết định giá thật.
- Quyết định tồn kho thật.
- Tạo order.
- Lưu cart thật trong local storage thay cho session.
- Bỏ qua validation của server.
- Tự ý cho phép checkout khi server báo lỗi.

---

# 4. User stories

## US01 — Thêm sản phẩm vào giỏ không reload trang

Là khách hàng, tôi muốn nhấn “Thêm vào giỏ hàng” và thấy sản phẩm được thêm ngay mà không reload trang.

Acceptance:

- Nút chuyển sang loading khi request chạy.
- Nếu thành công, cart badge tăng.
- Hiển thị thông báo “Đã thêm sản phẩm vào giỏ hàng”.
- Nếu hết hàng, hiển thị lỗi rõ ràng.
- Nếu JavaScript tắt, form vẫn submit bình thường.

## US02 — Tăng giảm số lượng trong cart page

Là khách hàng, tôi muốn nhấn nút `+` hoặc `-` để cập nhật số lượng và thấy thành tiền thay đổi ngay.

Acceptance:

- Quantity không nhỏ hơn 1.
- Quantity không vượt tồn kho.
- Line subtotal cập nhật theo response server.
- Cart total cập nhật theo response server.
- Nút checkout bị disable nếu cart không hợp lệ.
- Có debounce để không gửi quá nhiều request.

## US03 — Nhập số lượng thủ công

Là khách hàng, tôi muốn nhập số lượng bằng bàn phím và hệ thống tự cập nhật sau khi tôi dừng nhập.

Acceptance:

- Dùng debounce khoảng 300–600ms.
- Không gửi request với input rỗng hoặc không hợp lệ.
- Khi blur, normalize quantity.
- Nếu server trả lỗi, input quay về số lượng hợp lệ gần nhất hoặc hiển thị lỗi.

## US04 — Xóa sản phẩm khỏi giỏ

Là khách hàng, tôi muốn xóa sản phẩm khỏi giỏ mà không reload trang.

Acceptance:

- Có confirm nhẹ hoặc undo tùy scope.
- Item row biến mất khi server xác nhận.
- Cart total cập nhật.
- Nếu cart rỗng, hiển thị empty state.
- Header cart badge về số đúng.

## US05 — Checkout summary cập nhật động

Là khách hàng, tôi muốn summary ở checkout phản ánh đúng giỏ hàng hiện tại.

Acceptance:

- Summary lấy từ server.
- Không tin tổng tiền client.
- Nếu giá hoặc tồn kho thay đổi, hiển thị cảnh báo.
- Final checkout vẫn tính lại trong transaction.

---

# 5. UI/UX đề xuất

## 5.1. Product detail

Khu vực add-to-cart nên có:

- Giá hiện tại.
- Giá niêm yết, nếu có.
- Trạng thái tồn kho.
- Quantity stepper.
- Nút “Thêm vào giỏ hàng”.
- Thông báo lỗi inline.
- Loading state.

Ví dụ layout:

    [ - ] [ 1 ] [ + ]   [ Thêm vào giỏ hàng ]

Khi quantity thay đổi trên product detail:

- Có thể cập nhật preview tạm tính.
- Preview phải ghi nhận là hiển thị, không phải nguồn tính order.
- Khi add-to-cart, server trả lại cart state chính thức.

## 5.2. Cart page

Mỗi dòng item:

- Ảnh sản phẩm.
- Tên sản phẩm.
- Màu.
- Dung lượng.
- Đơn giá.
- Quantity stepper.
- Thành tiền.
- Nút xóa.

Quantity stepper:

    [ - ] [ input number ] [ + ]

Trạng thái:

- Loading nhỏ ở row đang cập nhật.
- Không khóa toàn bộ cart nếu chỉ một row đang update.
- Có thể disable checkout button khi có request đang pending.

## 5.3. Header cart badge

Header cart icon nên hiển thị:

- Font Awesome cart icon.
- Số lượng item.
- Cập nhật ngay sau add/update/remove.

Nếu cart rỗng:

- Badge ẩn hoặc hiển thị `0` tùy design.
- Phải nhất quán toàn website.

## 5.4. Toast hoặc flash message

Thông báo nên ngắn:

- “Đã thêm sản phẩm vào giỏ hàng.”
- “Đã cập nhật số lượng.”
- “Sản phẩm đã được xóa khỏi giỏ hàng.”
- “Số lượng vượt quá tồn kho hiện có.”
- “Giá hoặc tồn kho đã thay đổi, vui lòng kiểm tra lại.”

---

# 6. Backend contract

## 6.1. Routes hiện có

Có thể dùng các routes đã có:

    POST   /cart/items
    PATCH  /cart/items/{variant}
    DELETE /cart/items/{variant}
    DELETE /cart

Nếu cần summary:

    GET /cart/summary

Không bắt buộc tạo route mới nếu response từ các route hiện có đã đủ.

## 6.2. Add-to-cart request

Request:

    {
        "variant_id": 15,
        "quantity": 2
    }

Không nhận làm nguồn tin cậy:

- `unit_price`
- `line_subtotal`
- `cart_subtotal`
- `grand_total`
- `product_name`

## 6.3. Update quantity request

Request:

    {
        "quantity": 3
    }

Server kiểm tra:

- Variant tồn tại.
- Product active.
- Variant active.
- Quantity là integer.
- Quantity >= 1.
- Quantity <= stock.

## 6.4. Success response

Response JSON đề xuất:

    {
        "success": true,
        "message": "Đã cập nhật giỏ hàng.",
        "data": {
            "variant_id": 15,
            "quantity": 3,
            "unit_price": 19990000,
            "line_subtotal": 59970000,
            "cart_count": 3,
            "cart_subtotal": 59970000,
            "shipping_fee": 0,
            "grand_total": 59970000,
            "stock_quantity": 8,
            "formatted": {
                "unit_price": "19.990.000 ₫",
                "line_subtotal": "59.970.000 ₫",
                "cart_subtotal": "59.970.000 ₫",
                "shipping_fee": "0 ₫",
                "grand_total": "59.970.000 ₫"
            }
        }
    }

Có thể trả cả integer và formatted string.

Khuyến nghị:

- Integer dùng cho logic nhỏ phía UI.
- Formatted dùng để render nhất quán.
- Server vẫn là nơi tạo formatted string nếu muốn đảm bảo format VNĐ đồng nhất.

## 6.5. Cart summary response

    {
        "success": true,
        "data": {
            "cart_count": 3,
            "cart_subtotal": 59970000,
            "shipping_fee": 0,
            "grand_total": 59970000,
            "items": [
                {
                    "variant_id": 15,
                    "quantity": 3,
                    "unit_price": 19990000,
                    "line_subtotal": 59970000,
                    "stock_quantity": 8,
                    "is_available": true,
                    "formatted": {
                        "unit_price": "19.990.000 ₫",
                        "line_subtotal": "59.970.000 ₫"
                    }
                }
            ],
            "formatted": {
                "cart_subtotal": "59.970.000 ₫",
                "shipping_fee": "0 ₫",
                "grand_total": "59.970.000 ₫"
            }
        }
    }

## 6.6. Validation error

HTTP 422:

    {
        "message": "Dữ liệu không hợp lệ.",
        "errors": {
            "quantity": [
                "Số lượng không được vượt quá tồn kho."
            ]
        }
    }

## 6.7. Conflict response

Dùng khi giá, stock hoặc trạng thái sản phẩm thay đổi.

HTTP 409:

    {
        "success": false,
        "message": "Giá hoặc tồn kho đã thay đổi. Vui lòng kiểm tra lại giỏ hàng.",
        "data": {
            "variant_id": 15,
            "quantity": 2,
            "stock_quantity": 1,
            "cart_count": 2,
            "cart_subtotal": 39980000,
            "shipping_fee": 0,
            "grand_total": 39980000,
            "formatted": {
                "cart_subtotal": "39.980.000 ₫",
                "shipping_fee": "0 ₫",
                "grand_total": "39.980.000 ₫"
            }
        }
    }

jQuery phải cập nhật UI theo response này.

---

# 7. jQuery behavior

## 7.1. File đề xuất

    resources/js/cart.js

Nếu tách nhỏ:

    resources/js/cart/add-to-cart.js
    resources/js/cart/quantity-stepper.js
    resources/js/cart/cart-summary.js

MVP chỉ cần một file `cart.js` nếu code chưa dài.

## 7.2. Data attributes

Blade markup nên dùng data attributes:

    data-cart-form
    data-add-to-cart
    data-cart-item
    data-cart-item-variant-id
    data-quantity-input
    data-quantity-decrease
    data-quantity-increase
    data-line-subtotal
    data-cart-subtotal
    data-shipping-fee
    data-grand-total
    data-cart-count
    data-remove-cart-item

Không dùng inline handler:

    onclick="..."

## 7.3. Debounce

Dùng debounce cho input quantity.

Khoảng đề xuất:

    400ms

Pseudo behavior:

1. User nhập quantity.
2. Clear timer cũ.
3. Set timer mới.
4. Sau 400ms không thay đổi, gửi PATCH.
5. Nếu user blur trước, gửi ngay.

Không debounce nút `+` và `-` quá lâu nếu muốn cảm giác nhanh. Có thể:

- Update visual preview ngay.
- Debounce server request.
- Hoặc gửi request ngay nhưng disable button ngắn.

Khuyến nghị đơn giản:

- Button `+` và `-` gửi request sau debounce 250–400ms.
- Input manual debounce 500ms.

## 7.4. Optimistic UI

Có thể dùng optimistic UI nhẹ:

- Tạm tăng số lượng trên input.
- Tạm tính line subtotal bằng unit price hiện đang hiển thị.
- Hiển thị trạng thái “Đang cập nhật...”.

Sau response:

- Ghi đè bằng số liệu server trả về.

Nếu server báo lỗi:

- Khôi phục quantity hợp lệ.
- Hiển thị error.

## 7.5. Loading state

Trong lúc update một row:

- Disable nút `+` và `-` của row đó.
- Có thể giữ input disabled tạm thời.
- Hiển thị spinner nhỏ.
- Không disable toàn bộ cart trừ khi cần.

Checkout button:

- Disable nếu có request cart đang pending.
- Enable lại khi tất cả request hoàn tất và cart hợp lệ.

## 7.6. Error handling

Xử lý:

- 419: “Phiên làm việc đã hết hạn, vui lòng tải lại trang.”
- 422: Hiển thị validation message.
- 403: “Bạn không có quyền thực hiện thao tác này.”
- 404: “Sản phẩm không còn tồn tại.”
- 409: Refresh cart summary và hiển thị cảnh báo.
- 500: “Có lỗi xảy ra, vui lòng thử lại.”

## 7.7. CSRF

Blade layout cần có:

    <meta name="csrf-token" content="{{ csrf_token() }}">

jQuery setup:

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

---

# 8. Server-side implementation notes

## 8.1. CartService

CartService nên có method trả summary chuẩn:

    getSummary(): array

Có thể gồm:

- Items.
- Cart count.
- Subtotal.
- Shipping fee.
- Grand total.
- Formatted values.
- Warnings.

Tất cả route cart dùng chung method này.

## 8.2. Money formatting

Tạo helper hoặc service:

    format_vnd(int $amount): string

Ví dụ:

    19990000 -> 19.990.000 ₫

Không format tiền thủ công rải rác trong nhiều Blade.

## 8.3. Shipping fee

Nếu MVP shipping fee = 0:

- Vẫn trả `shipping_fee`.
- Vẫn hiển thị “Miễn phí” hoặc “0 ₫”.
- Sau này dễ mở rộng.

Nếu shipping fee cố định:

- Tính ở server.
- Không để JS tự quyết định.

## 8.4. Stock validation

Khi update cart:

- Server đọc lại stock.
- Nếu quantity > stock, trả 422 hoặc 409.
- Có thể tự giảm về max stock nếu UX muốn, nhưng phải báo rõ.

Khuyến nghị:

- Nếu quantity vượt stock, trả 422.
- UI giữ lại quantity cũ hợp lệ.
- Hiển thị tồn kho hiện tại.

## 8.5. Price changes

Nếu giá thay đổi sau khi user mở cart:

- Server response trả giá mới.
- UI hiển thị cảnh báo.
- Cart summary cập nhật theo giá mới.
- Checkout vẫn tính lại lần nữa.

---

# 9. Blade requirements

## 9.1. Product detail add-to-cart form

Form vẫn hoạt động khi không có JavaScript.

Yêu cầu:

- `method="POST"`
- CSRF token.
- Hidden `variant_id`.
- Quantity input.
- Submit button.
- Data attributes cho jQuery.

Không gửi price hidden input.

## 9.2. Cart item row

Mỗi row cần data attributes:

- Variant ID.
- Current quantity.
- Update URL.
- Remove URL.

Không cần lưu giá authoritative trong hidden input.

Có thể lưu unit price hiển thị cho optimistic preview, nhưng server response sẽ ghi đè.

## 9.3. Empty cart state

Khi cart rỗng sau AJAX remove:

- Ẩn cart table.
- Hiện empty state.
- Nút “Tiếp tục mua sắm”.
- Cart badge cập nhật.

## 9.4. Checkout summary

Checkout summary cần data attributes:

- Cart subtotal.
- Shipping fee.
- Grand total.
- Summary warning area.

---

# 10. Accessibility

- Quantity input có label hoặc aria-label.
- Nút tăng giảm có aria-label.
- Loading state không chỉ dựa vào màu.
- Error message gắn với input liên quan.
- Cart badge có text hỗ trợ screen reader.
- Toast không che mất thao tác chính.
- Keyboard vẫn dùng được.

Ví dụ:

    aria-label="Tăng số lượng iPhone 16 Pro"
    aria-label="Giảm số lượng iPhone 16 Pro"

---

# 11. Test plan

## 11.1. Feature tests

- Add-to-cart JSON success.
- Add-to-cart SSR fallback redirect.
- Update quantity JSON success.
- Update quantity vượt tồn kho.
- Update quantity không hợp lệ.
- Remove item JSON success.
- Remove item SSR fallback.
- Clear cart.
- Cart summary trả integer VNĐ.
- Client gửi price giả bị bỏ qua.
- Product inactive không thêm được.
- Variant inactive không thêm được.
- Quantity update trả canonical total.

## 11.2. Browser hoặc manual test

- Nhấn add-to-cart không reload.
- Cart badge tăng.
- Nhấn `+`.
- Nhấn `-`.
- Nhập quantity nhanh, debounce hoạt động.
- Remove item.
- Cart rỗng hiển thị empty state.
- Tắt JavaScript, form vẫn hoạt động.
- Simulate stock thay đổi.
- Simulate price thay đổi.
- Mobile thao tác được.

## 11.3. Optional JavaScript test

Nếu project có JS test setup thì test debounce logic.

Nếu không có, manual test và feature test backend là đủ cho đồ án.

---

# 12. Acceptance criteria

Tính năng hoàn thành khi:

- Add-to-cart hoạt động bằng AJAX.
- Add-to-cart vẫn hoạt động khi JavaScript tắt.
- Quantity tăng giảm động.
- Nhập quantity có debounce.
- Line subtotal cập nhật động.
- Cart subtotal cập nhật động.
- Grand total cập nhật động.
- Header cart badge cập nhật động.
- Server response là nguồn dữ liệu chính thức.
- Client price giả bị bỏ qua.
- Vượt tồn kho được xử lý rõ.
- Giá thay đổi được xử lý rõ.
- Có loading state.
- Có error state.
- Không dùng framework JavaScript mới.
- Không có inline event handler.
- Có feature test cho backend contract.
- Mobile friendly.

---

# 13. Prompt cho Cursor

Dùng prompt này khi bắt đầu implement.

    Read:
    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/DYNAMIC_UI.md
    @docs/ROUTES.md
    @docs/UI_GUIDELINES.md
    @docs/EXTRA_ENHANCED_ADD_TO_CART_UX.md

    Implement the Enhanced Add-to-Cart UX feature.

    Scope:
    - Use jQuery only.
    - Keep SSR form fallback.
    - Enhance add-to-cart with AJAX.
    - Enhance cart quantity increase/decrease with AJAX.
    - Debounce manual quantity input.
    - Update line subtotal, cart subtotal, shipping fee, grand total, and cart badge from server response.
    - Use server-authoritative prices, stock, and totals.
    - Do not trust client-submitted price or totals.
    - Handle loading, validation, stock conflict, price conflict, CSRF expiry, and generic errors.
    - Use Font Awesome icons where appropriate.
    - Do not add any new JavaScript library.
    - Do not refactor unrelated code.
    - Add feature tests for JSON responses and server-side recalculation.

    Before editing:
    1. Inspect current cart routes, CartService, Blade views, and JavaScript files.
    2. Report files that will be modified.
    3. Report any missing backend contract.
    4. Then implement the smallest complete vertical slice.

    After implementation:
    - Run focused tests.
    - Run php artisan test.
    - Run npm run build.
    - Report changed files and remaining limitations.

---

# 14. Phạm vi mở rộng sau này

Sau khi tính năng này ổn định, có thể bổ sung:

- Mini cart dropdown.
- Cart drawer.
- Undo remove item.
- Free shipping progress bar.
- Multi-tab cart sync.
- Recently added item highlight.
- Cart recommendation.
- Persistent cart after login.

Không triển khai các phần này trong phase hiện tại.
