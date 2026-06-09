# PROMPT KHỞI ĐỘNG PROJECT CHO CURSOR

Sao chép toàn bộ nội dung bên dưới và gửi cho Cursor Agent ở Plan Mode hoặc Agent Mode.

---

Bạn đang bắt đầu triển khai đồ án website bán điện thoại Apple trong repository hiện tại.

Hãy đọc kỹ:

- @AGENTS.md
- @docs/SPEC.md
- @docs/ARCHITECTURE.md
- @docs/DATABASE.md
- @docs/ROUTES.md
- @docs/UI_GUIDELINES.md
- @docs/IMAGE_STRATEGY.md
- @docs/TASKS.md
- @docs/CURSOR_WORKFLOW.md

Mục tiêu hiện tại là hoàn thành duy nhất Phase 0 trong @docs/TASKS.md.

## Phạm vi

1. Kiểm tra repository và môi trường:
   - PHP.
   - Composer.
   - Node.js.
   - npm.
   - MySQL.
   - Laravel hiện có, nếu có.

2. Nếu chưa có Laravel project, khởi tạo trực tiếp trong repository hiện tại.

3. Nếu đã có Laravel project:
   - Không khởi tạo lại.
   - Không xóa code hiện có.
   - Tiếp tục theo convention đang có nếu không xung đột tài liệu.

4. Cấu hình:
   - Laravel Blade.
   - Vite.
   - Tailwind CSS.
   - jQuery qua npm và Vite.
   - MySQL qua `.env.example`.
   - Locale giao diện tiếng Việt.
   - Timezone Việt Nam.

5. Không cài:
   - React.
   - Vue.
   - Livewire.
   - Inertia.
   - Alpine.js.
   - Bootstrap.
   - Frontend framework khác.
   - Search engine ngoài.
   - Package xử lý ảnh.

6. Tạo nền tảng giao diện:
   - Customer layout.
   - Admin layout.
   - Header.
   - Footer.
   - Admin sidebar cơ bản.
   - Flash message component.
   - Trang chủ placeholder.
   - Admin dashboard placeholder.

7. Áp dụng chiến lược hình ảnh Phase 0:
   - Đọc @docs/IMAGE_STRATEGY.md.
   - Sao chép placeholder từ:
     `starter-assets/public/images/placeholders/product-placeholder.svg`
   - Đến:
     `public/images/placeholders/product-placeholder.svg`
   - Không tải ảnh ngoài.
   - Không dùng hotlink.
   - Không làm upload admin trong Phase 0.
   - Không cài package xử lý ảnh.
   - Chuẩn bị một Blade component hình ảnh sản phẩm đơn giản chỉ khi cấu trúc hiện tại phù hợp; nếu model product chưa tồn tại, chỉ tạo placeholder và helper UI tối thiểu, không tạo model hoặc migration sớm.

8. Không tạo migration nghiệp vụ trong Phase 0:
   - products.
   - product_variants.
   - orders.
   - order_items.
   - product_images.

9. Không triển khai:
   - Authentication đầy đủ.
   - Search.
   - Filter.
   - Cart.
   - Checkout.
   - CRUD admin.
   - Review hoặc comment.
   - Thanh toán online.

## Quy trình

Trước khi sửa:

1. Chạy `git status`.
2. Kiểm tra cấu trúc repository.
3. Báo cáo:
   - Project đã có Laravel chưa.
   - Version công cụ.
   - File sẽ tạo hoặc sửa.
   - Package sẽ cài.
   - Giả định.
   - Xung đột tài liệu, nếu có.

Sau đó triển khai Phase 0.

## Kiểm tra

Chạy các lệnh phù hợp:

    php artisan about
    php artisan route:list
    php artisan test
    npm run build
    git diff
    git status

Chỉ chạy `php artisan migrate:fresh` khi chắc chắn database là database phát triển trống hoặc database test.

## Acceptance criteria

- Laravel chạy được.
- Trang chủ Blade render được.
- Admin dashboard placeholder render được.
- Tailwind hoạt động.
- jQuery được bundle bằng Vite.
- Layout customer và admin tồn tại.
- UI tiếng Việt.
- Responsive cơ bản.
- Placeholder sản phẩm cục bộ tồn tại.
- Không có external image hotlink.
- Test chạy thành công.
- Build thành công.
- Không có secret.
- Không triển khai phase sau.
- Không có thay đổi ngoài phạm vi.

## Báo cáo cuối

1. Trạng thái Phase 0.
2. File đã tạo.
3. File đã sửa.
4. Package đã cài và lý do.
5. Command đã chạy.
6. Kết quả test và build.
7. Cấu hình thủ công còn lại.
8. Giới hạn còn lại.
9. Task tiếp theo theo @docs/TASKS.md.

Không tự động bắt đầu Phase 1.
