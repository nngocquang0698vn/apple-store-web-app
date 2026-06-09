# Apple Store Cursor Kit

Bộ tài liệu này dùng để hướng dẫn Cursor xây dựng đồ án website bán sản phẩm Apple theo hướng server-side rendering.

## Phạm vi đã chốt

- Backend: PHP và Laravel.
- Database: MySQL.
- Giao diện: Laravel Blade và Tailwind CSS.
- JavaScript: jQuery, chỉ dùng để nâng cấp trải nghiệm.
- Có khu vực khách hàng và quản trị viên.
- Có tìm kiếm, lọc, sắp xếp và phân trang sản phẩm.
- Danh mục chính gồm iPhone, iPad, iPod và phụ kiện sạc.
- Giá được lưu và hiển thị bằng Việt Nam đồng.
- Font Awesome Free được dùng cho icon giao diện phù hợp.
- Giỏ hàng lưu bằng session.
- Đặt hàng đơn giản bằng COD.
- Không có đánh giá hoặc bình luận sản phẩm.
- Không có thanh toán trực tuyến.
- Không dùng React, Vue, Livewire, Inertia hoặc SPA.

## Cấu trúc tài liệu

- `AGENTS.md`: quy tắc gốc dành cho Cursor Agent.
- `docs/SPEC.md`: đặc tả chức năng và tiêu chí nghiệm thu.
- `docs/ARCHITECTURE.md`: kiến trúc và quy tắc tổ chức mã nguồn.
- `docs/DATABASE.md`: thiết kế cơ sở dữ liệu.
- `docs/ROUTES.md`: danh sách route và contract request/response.
- `docs/UI_GUIDELINES.md`: định hướng giao diện và responsive.
- `docs/IMAGE_STRATEGY.md`: chiến lược placeholder, ảnh demo và upload admin.
- `docs/DYNAMIC_UI.md`: kiến trúc giao diện động bằng jQuery và AJAX.
- `docs/JS_LIBRARY_PROPOSAL_TEMPLATE.md`: mẫu xin phép thêm thư viện JavaScript.
- `docs/PROJECT_START_PROMPT.md`: prompt khởi động Phase 0 cho Cursor.
- `docs/TASKS.md`: kế hoạch triển khai theo phase.
- `docs/CURSOR_WORKFLOW.md`: cách dùng Cursor và prompt mẫu.
- `docs/DEVELOPMENT_WINDOWS_LARAGON.md`: thiết lập và vận hành dự án trên Windows với Laragon.
- `.cursor/rules/*.mdc`: các Project Rules bổ sung.
- `.cursorignore`: các đường dẫn Cursor không nên đọc hoặc index.
- `starter-assets/`: tài nguyên khởi tạo an toàn, gồm placeholder SVG.

## Môi trường development đã chốt

- Hệ điều hành: Windows.
- Local development environment: Laragon.
- Terminal: PowerShell hoặc Laragon Terminal.
- Web server và MySQL: do Laragon quản lý.
- Project khuyến nghị đặt trong `C:\laragon\www\apple-store`.

Đọc `docs/DEVELOPMENT_WINDOWS_LARAGON.md` trước khi khởi tạo project.

## Cách sử dụng

1. Sao chép toàn bộ thư mục này vào root của repository.
2. Đọc và chỉnh lại tên dự án trong `docs/SPEC.md` nếu cần.
3. Đặt project trong thư mục `www` của Laragon và khởi tạo Laravel theo phiên bản mà nhóm đã thống nhất.
4. Trong Cursor, yêu cầu Agent đọc `AGENTS.md` và tài liệu liên quan trước khi lập kế hoạch.
5. Thực hiện từng phase trong `docs/TASKS.md`.
6. Mỗi feature phải được review diff và chạy test trước khi commit.

## Nguyên tắc quan trọng

`AGENTS.md` là tài liệu chỉ dẫn chính. Các file `.cursor/rules` chỉ bổ sung chỉ dẫn theo loại file. Không đặt yêu cầu nghiệp vụ mới vào rule; hãy cập nhật `docs/SPEC.md`.

## Chiến lược hình ảnh

Ngay từ Phase 0, sao chép:

    starter-assets/public/images/placeholders/product-placeholder.svg

đến:

    public/images/placeholders/product-placeholder.svg

Không tìm hoặc hotlink ảnh bên ngoài trong giai đoạn khởi tạo. Xem chi tiết tại `docs/IMAGE_STRATEGY.md`.

## Giao diện động và dữ liệu tin cậy

Website dùng jQuery để tạo trải nghiệm động:

- Chọn biến thể cập nhật giá, ảnh và tồn kho.
- Tìm kiếm, lọc và phân trang có thể dùng AJAX.
- Thêm, sửa và xóa giỏ hàng không cần reload toàn trang.
- Cart badge và checkout summary cập nhật ngay.

Laravel vẫn là nguồn dữ liệu tin cậy cho giá, tồn kho, phí vận chuyển và tổng tiền. JavaScript chỉ hiển thị preview hoặc dữ liệu server trả về.

## Chính sách thư viện JavaScript

jQuery là thư viện mặc định. Cursor có thể đề xuất thư viện mới, nhưng không được cài đặt hoặc sửa lock file trước khi human xác nhận rõ ràng.
