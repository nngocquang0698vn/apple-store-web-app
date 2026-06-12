# GIAO DIỆN ĐỘNG VỚI JQUERY

## 1. Mô hình

Website dùng:

    Server-side rendering
    + jQuery progressive enhancement
    + Laravel AJAX endpoints
    + server-authoritative business data

Không xây SPA.

## 2. Phần nên động

### Product detail

- Chọn màu và dung lượng.
- Cập nhật variant.
- Cập nhật giá.
- Cập nhật tồn kho.
- Cập nhật ảnh.
- Bật hoặc tắt nút add-to-cart.

### Product discovery

- Debounce search.
- Filter và sort AJAX.
- Partial pagination.
- Update URL.
- Back và Forward.

### Cart

- Add.
- Update quantity.
- Remove.
- Clear.
- Cart badge.
- Line subtotal.
- Cart subtotal.
- Shipping fee.
- Grand total.

### Checkout

- Refresh summary.
- Hiển thị price hoặc stock change.
- Loading state khi đặt hàng.
- Chống thao tác lặp ở UI.

## 3. Server là nguồn tin cậy

Không tin:

- Giá trong DOM.
- Hidden input.
- Data attribute.
- Local storage.
- JavaScript object.
- Tổng tiền client gửi.

Server đọc lại variant, active state, price, stock và shipping configuration.

## 4. Preview phía client

JavaScript có thể tính preview để phản hồi nhanh:

    preview = displayed_unit_price * quantity

Sau AJAX response, UI phải thay preview bằng `response.data.line_subtotal`.

Final checkout luôn tính lại trong transaction.

## 5. Response mẫu

    {
        "success": true,
        "message": "Đã cập nhật giỏ hàng.",
        "data": {
            "variant_id": 15,
            "quantity": 2,
            "unit_price": 19990000,
            "line_subtotal": 39980000,
            "cart_count": 2,
            "cart_subtotal": 39980000,
            "shipping_fee": 0,
            "grand_total": 39980000,
            "stock_quantity": 8
        }
    }

Tiền dùng integer VNĐ.

## 6. Error handling

- 419: CSRF hoặc session hết hạn.
- 422: Validation.
- 403: Không có quyền.
- 404: Resource không tồn tại.
- 409: Giá, trạng thái hoặc tồn kho thay đổi.
- 500: Lỗi server.

UI hiển thị thông báo tiếng Việt và phục hồi control khi request thất bại.

## 7. SSR fallback

Các core action vẫn có form bình thường:

- Add cart.
- Update cart.
- Remove cart.
- Search.
- Filter.
- Checkout.

jQuery intercept form khi hoạt động. Nếu JavaScript lỗi, browser submit bình thường.

## 8. Format tiền

Server trả integer. Frontend dùng một formatter chung để hiển thị:

    19.990.000 ₫

Formatter không thay đổi giá trị nghiệp vụ.

## 9. Testing

- JSON success.
- JSON validation.
- Client price ignored.
- Canonical totals returned.
- Price conflict.
- Stock conflict.
- SSR redirect fallback.
- Session cart update.
- Final checkout recalculation.
