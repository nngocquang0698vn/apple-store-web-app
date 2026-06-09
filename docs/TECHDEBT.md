# Tech debt

Danh sách cải tiến đã thử hoặc đã lên kế hoạch nhưng **chưa merge** vì rủi ro, thiếu thời gian test, hoặc lỗi runtime. Ưu tiên xử lý trước khi ship production.

## TD-001: Tự động cập nhật số lượng giỏ hàng (không nút "Cập nhật")

**Trạng thái:** Đã thử, rollback.

**Mô tả:** Bỏ nút "Cập nhật" cạnh ô số lượng; đổi số lượng → debounce → PATCH AJAX; preview tạm tính dòng bằng JS; server trả giá chuẩn.

**Lý do rollback:** Lỗi `500` khi `PATCH /cart/items/{variant}` trên môi trường Laragon (cần điều tra `CartService::buildSummary` / session / race request). UX tự động cũng gây request trùng (`input` + `change` + debounce).

**Khi làm lại:**

1. Dùng `POST` + `_method=PATCH` thay vì HTTP PATCH thuần (tương thích Apache/Laragon).
2. Một luồng sync duy nhất (debounce **hoặc** `change`, không cả hai).
3. Chặn request song song (`syncInFlight` / abort request cũ).
4. `422` trả kèm `data` summary để đồng bộ lại input.
5. Feature test mô phỏng browser: `POST` + `X-Requested-With` + `Accept: application/json`.
6. Ghi log exception thật trước khi bật lại trên production.

**UI hiện tại:** Nút **Cập nhật** + form `data-action="update-cart-item"` (AJAX hoặc SSR fallback).

---

## TD-002: Checkout summary tự động (không nút "Cập nhật tổng tiền")

**Trạng thái:** Đã thử một phần, rollback nút refresh tự động.

**Mô tả:** Bỏ nút "Cập nhật tổng tiền"; tự gọi `GET /checkout/summary` khi vào trang và khi `visibilitychange`.

**Lý do rollback:** Gắn với TD-001 trong cùng đợt UX; giữ nút refresh rõ ràng để user chủ động đồng bộ khi nghi ngờ giá/tồn kho thay đổi.

**Khi làm lại:**

1. Refresh im lặng khi load trang (không toast success mỗi lần).
2. Refresh khi quay lại tab nếu `can_checkout === false`.
3. Giữ nút refresh là tùy chọn hoặc ẩn sau khi auto-sync ổn định.

**UI hiện tại:** Nút **Cập nhật tổng tiền** + auto-refresh lần đầu khi mở checkout.

---

## TD-003: `data-action` selector conflict (product detail)

**Trạng thái:** Đã fix (không rollback).

**Mô tả:** `data-action="add-to-cart"` trên `<form>` bị `product-variant.js` nhầm là nút → nền xanh phủ cả form.

**Fix đã áp dụng:** `data-product-add-button` trên nút submit; form giữ `data-action="add-to-cart"` cho `cart.js`.

---

## TD-004: Validation JSON cho cart AJAX

**Trạng thái:** Một phần đã fix.

**Đã có:**

- `ValidationException::withMessages()` dùng mảng message.
- `bootstrap/app.php`: `shouldRenderJsonWhen` gồm `expectsJson()`.

**Còn thiếu:**

- Response `422` kèm `data` summary khi update cart fail (đã rollback cùng TD-001).
- Test browser-like cho mọi mutation cart.

---

## Thứ tự ưu tiên đề xuất

1. Điều tra và fix root cause `500` trên `cart.items.update` (nếu vẫn tái hiện với UI nút Cập nhật).
2. TD-001 với test đầy đủ.
3. TD-002 sau khi cart sync ổn định.
4. TD-004 hoàn thiện error contract JSON.

---

## Liên quan

- `docs/FIELD_NOTES.md` — bài học chung khi triển khai (giữ bugfix TD-003, TD-004).
- `docs/DYNAMIC_UI.md` — spec giao diện động mục tiêu.
