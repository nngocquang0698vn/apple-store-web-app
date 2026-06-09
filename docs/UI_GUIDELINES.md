# HƯỚNG DẪN GIAO DIỆN

## 1. Định hướng

Giao diện:

- Tối giản.
- Hiện đại.
- Sạch.
- Ưu tiên sản phẩm.
- Tiếng Việt rõ ràng.
- Không sao chép nguyên bản website Apple.
- Không tự nhận là đại lý chính thức nếu không có căn cứ.

## 2. Màu sắc

Gợi ý:

- Nền chính: trắng hoặc xám rất nhạt.
- Chữ chính: xám đậm gần đen.
- Chữ phụ: xám trung tính.
- Màu nhấn: xanh dương.
- Thành công: xanh lá.
- Cảnh báo: vàng hoặc cam.
- Lỗi: đỏ.

Không dùng quá nhiều màu nhấn.

## 3. Typography

- Font sans-serif dễ đọc.
- Heading có phân cấp rõ.
- Giá sản phẩm nổi bật.
- Không dùng quá nhiều kích thước chữ.
- Nội dung mô tả có chiều rộng dễ đọc.

## 4. Spacing và radius

- Dùng hệ spacing của Tailwind.
- Card và input dùng radius nhất quán.
- Không lạm dụng bo tròn lớn.
- Form đủ khoảng cách để dùng trên mobile.

## 5. Layout

### Customer layout

- Header.
- Logo hoặc tên cửa hàng.
- Search bar.
- Navigation.
- Cart indicator.
- Account menu.
- Main content.
- Footer.

### Admin layout

- Sidebar desktop.
- Top bar.
- Main content.
- Breadcrumb.
- Flash message.
- Table hoặc card responsive.

## 6. Responsive

### Mobile

- Search dễ truy cập.
- Filter mở dạng drawer hoặc section thu gọn.
- Product grid 2 cột nếu đủ rộng, 1 cột nếu cần.
- Cart chuyển từ table sang card.
- Button chính full-width khi phù hợp.

### Tablet

- Product grid 2 đến 3 cột.
- Admin sidebar có thể thu gọn.
- Form tối đa 2 cột.

### Desktop

- Product grid 3 đến 4 cột.
- Filter sidebar và result area.
- Admin dùng table.

## 7. Component cần có

- Button.
- Input.
- Select.
- Checkbox.
- Radio.
- Textarea.
- Form error.
- Alert.
- Badge.
- Product card.
- Price display.
- Breadcrumb.
- Pagination.
- Empty state.
- Loading state.
- Modal hoặc confirm dialog.
- Order status badge.
- Admin table.
- Mobile filter drawer.

## 8. Product card

Hiển thị:

- Ảnh chính.
- Dòng sản phẩm, nếu cần.
- Tên.
- Giá thấp nhất.
- Giá cũ khi có khuyến mãi.
- Trạng thái còn hàng.
- Badge nổi bật, nếu có.
- Link xem chi tiết.

Không đặt quá nhiều nút.

Card phải có chiều cao nội dung tương đối đồng nhất.

## 8.1. Hình ảnh sản phẩm

- Product card dùng container tỷ lệ 1:1.
- Dùng `object-contain`.
- Dùng placeholder cục bộ khi thiếu ảnh.
- Product card dùng lazy loading.
- Ảnh phía trên fold của trang chi tiết không bắt buộc lazy load.
- Alt text phải mô tả sản phẩm.
- Không dùng external hotlink.
- Không làm layout thay đổi mạnh khi ảnh tải xong.
- Không thêm logo Apple vào placeholder.
- Xem `docs/IMAGE_STRATEGY.md`.

## 9. Search và filter UI

### Search bar

- Có label ẩn hoặc visible label.
- Placeholder ví dụ: `Tìm iPhone theo tên hoặc SKU`.
- Có nút tìm kiếm.
- Có nút xóa khi có query.

### Filter

Nhóm:

- Danh mục.
- Dòng sản phẩm.
- Màu.
- Dung lượng.
- Khoảng giá.
- Còn hàng.
- Nổi bật.

Hiển thị filter đang áp dụng dưới dạng chip hoặc summary.

Có nút:

- `Áp dụng`.
- `Xóa bộ lọc`.

SSR form dùng GET.

### Result header

Hiển thị:

- Số sản phẩm tìm thấy.
- Từ khóa.
- Select sắp xếp.
- Nút mở filter trên mobile.

### Empty state

Nội dung:

- Không tìm thấy sản phẩm phù hợp.
- Gợi ý bỏ bớt bộ lọc.
- Nút xóa bộ lọc.

## 10. Product detail

Bố cục:

- Gallery.
- Thông tin chính.
- Chọn màu.
- Chọn dung lượng.
- Giá.
- Stock.
- Quantity.
- Add to cart.
- Mô tả.
- Thông số.

Variant selector phải thể hiện disabled cho tổ hợp không tồn tại hoặc hết hàng.

jQuery chỉ cập nhật giao diện; server vẫn validate.

## 11. Cart

Mỗi dòng:

- Ảnh.
- Tên.
- Màu.
- Dung lượng.
- Đơn giá.
- Quantity control.
- Thành tiền.
- Xóa.

Summary:

- Tạm tính.
- Phí vận chuyển.
- Tổng dự kiến.
- Nút checkout.

Có thông báo khi giá hoặc tồn kho thay đổi.

## 12. Checkout

- Form chia section rõ.
- Hiển thị order summary.
- Payment method là COD và không thể sửa sang phương thức khác.
- Nút đặt hàng có trạng thái loading để tránh submit lặp.
- Server phải chống double submit bằng transaction và logic phù hợp.

## 13. Validation

- Error gần field.
- Input lỗi có aria attributes phù hợp.
- Không chỉ dùng màu để biểu thị lỗi.
- Giữ old input.
- Focus field lỗi đầu tiên nếu dùng JavaScript.

## 14. Accessibility cơ bản

- Label cho input.
- Alt text ảnh.
- Button dùng phần tử button.
- Link dùng cho điều hướng.
- Keyboard focus rõ.
- Modal có thể đóng bằng bàn phím.
- Không dùng div thay button.
- Contrast đủ đọc.

## 15. jQuery convention

HTML:

    <button
        type="button"
        data-action="add-to-cart"
        data-variant-id="15"
    >
        Thêm vào giỏ hàng
    </button>

JavaScript bind theo data attribute.

Không dùng:

    onclick="addToCart(...)"

Không nhúng business data nhạy cảm hoặc tin cậy vào DOM.
