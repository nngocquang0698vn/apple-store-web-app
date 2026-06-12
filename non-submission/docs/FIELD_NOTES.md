# Ghi chú triển khai thực tế

Tài liệu này ghi lại các quyết định UX và kỹ thuật rút ra khi triển khai, không nằm trong spec ban đầu nhưng quan trọng khi làm sản phẩm thật.

Các hạng mục **chưa làm / đã rollback** được ghi trong `docs/TECHDEBT.md`.

## 1. `data-action` phải gắn đúng phần tử

**Vấn đề:** Gắn `data-action="add-to-cart"` lên `<form>` nhưng `product-variant.js` lại coi đó là nút submit → gán `bg-blue-600` cho cả form.

**Bài học:** Tách attribute theo vai trò:

- `data-action="add-to-cart"` — form (`cart.js` intercept submit)
- `data-product-add-button` — nút thêm giỏ (`product-variant.js`)

Không dùng một selector cho hai mục đích khác nhau.

## 2. Validation exception và JSON

Laravel `ValidationException::withMessages()` cần message dạng mảng (`['field' => ['Lỗi']]`), không phải chuỗi đơn — nếu không response JSON 422 sẽ lỗi khi render.

`bootstrap/app.php` cần `expectsJson()` trong `shouldRenderJsonWhen` để route web (cart/checkout) trả JSON khi dùng `Accept: application/json`.

## 3. Server-authoritative pricing

Luôn lặp lại trong code review:

- Không nhận `subtotal`, `shipping_fee`, `total_amount` từ client
- `CartService` / `PlaceOrder` đọc lại variant, giá, stock từ DB
- Session cart chỉ lưu `variant_id` + `quantity`

## 4. AJAX cart dùng POST method spoofing

Trên Laragon/Apache, gửi `POST` + `_method=PATCH|DELETE` ổn định hơn HTTP verb thuần từ jQuery. Luôn kèm:

- `X-CSRF-TOKEN`
- `X-Requested-With: XMLHttpRequest`
- `Accept: application/json`

## 5. Đặt tên file ghi chú

- `FIELD_NOTES.md` — bài học đã áp dụng
- `TECHDEBT.md` — việc hoãn lại hoặc cần làm lại
