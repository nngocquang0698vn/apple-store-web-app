# HƯỚNG DẪN GIAO DIỆN THÂN THIỆN VỚI KHÁCH HÀNG

## 1. Định hướng

Giao diện:

- Tối giản.
- Hiện đại.
- Sạch.
- Ưu tiên sản phẩm.
- Tiếng Việt rõ ràng.
- Không sao chép nguyên bản website Apple.
- Không tự nhận là đại lý chính thức nếu không có căn cứ.

## 1.1. Mục tiêu trải nghiệm

Khách hàng phải dễ:

- Nhận biết cửa hàng bán iPhone, iPad và phụ kiện.
- Tìm sản phẩm.
- Truy cập danh mục.
- Hiểu giá và biến thể.
- Nhìn thấy giỏ hàng.
- Hoàn thành checkout.

Ưu tiên hành trình mua hàng hơn hiệu ứng trang trí.

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

## 5.1. Customer header

Header nên có:

- Tên hoặc logo chữ.
- Navigation danh mục.
- Search.
- Account.
- Cart với Font Awesome icon và cart count.
- Mobile menu.

Không làm navigation quá dày.

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

## 6.1. Font Awesome

Dùng Font Awesome Free cho cart, search, account, filter, menu, chevron, trash, plus, minus, loading và order status khi phù hợp.

- Icon hỗ trợ text.
- Icon-only button có `aria-label`.
- Dùng nhất quán một style.
- Không dùng thêm icon library.

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

## 8.2. Hiển thị giá VNĐ

- Hiển thị `19.990.000 ₫`.
- Không có số thập phân.
- Giá bán nổi bật hơn giá niêm yết.
- Giá cũ có thể gạch ngang.
- Dùng helper hoặc Blade component chung.
- Không hard-code chuỗi giá ở nhiều view.

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

## 14.1. Trạng thái tương tác động

Mỗi thao tác jQuery cần có trạng thái:

- Idle.
- Loading.
- Success.
- Validation error.
- Price hoặc stock conflict.
- Session expiry.
- Server error.

Ví dụ:

- Disable add-to-cart khi request đang chạy.
- Dùng Font Awesome spinner khi phù hợp.
- Update cart count và tổng tiền không reload.
- Khôi phục control khi request lỗi.
- Không hiển thị thao tác thành công trước khi server xác nhận.
- Luôn giữ form fallback.

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

## 16. Layout trang chủ

Thứ tự đề xuất:

1. Header có search và cart.
2. Hero hoặc thông điệp chính.
3. Danh mục iPhone, iPad và phụ kiện.
4. Sản phẩm nổi bật.
5. Sản phẩm mới.
6. Chính sách giao hàng và bảo hành.
7. Footer.
