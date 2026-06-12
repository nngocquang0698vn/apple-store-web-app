# CHIẾN LƯỢC HÌNH ẢNH SẢN PHẨM

## 1. Mục tiêu

Phần hình ảnh không được làm chậm tiến độ của catalog, tìm kiếm, giỏ hàng và checkout.

Chiến lược triển khai theo ba giai đoạn:

1. Placeholder SVG cục bộ.
2. Ảnh demo cục bộ cho seed data.
3. Upload ảnh thật trong khu vực quản trị.

Không tải hoặc hotlink ảnh từ website bên ngoài trong luồng chạy của ứng dụng.

## 2. Giai đoạn 1: Placeholder

Ngay từ Phase 0, dự án phải có file:

    public/images/placeholders/product-placeholder.svg

Bộ kit cung cấp file mẫu tại:

    starter-assets/public/images/placeholders/product-placeholder.svg

Khi khởi tạo dự án, sao chép file mẫu vào đúng thư mục `public`.

Yêu cầu placeholder:

- Tỷ lệ vuông 1:1.
- Hình minh họa sản phẩm công nghệ trung tính.
- Không có logo Apple.
- Không có nhãn hiệu bên thứ ba.
- Phù hợp nền sáng.
- Dung lượng nhỏ.
- Không phụ thuộc Internet.

Placeholder được dùng khi sản phẩm chưa có ảnh.

## 3. Giai đoạn 2: Ảnh demo cục bộ

Sau khi product card và trang chi tiết đã ổn định, thêm một bộ ảnh demo nhỏ.

Vị trí đề xuất:

    storage/app/public/products/demo/

Ví dụ:

    storage/app/public/products/demo/iphone-13-blue.webp
    storage/app/public/products/demo/iphone-15-black.webp
    storage/app/public/products/demo/iphone-16-pink.webp

Chạy:

    php artisan storage:link

Seeder chỉ lưu đường dẫn tương đối:

    products/demo/iphone-16-pink.webp

Không lưu:

- Domain.
- URL localhost.
- URL CDN.
- URL ảnh từ website bên ngoài.

Nếu không có quyền sử dụng ảnh sản phẩm thật, tiếp tục dùng placeholder hoặc ảnh minh họa tự tạo.

## 4. Giai đoạn 3: Upload trong admin

Chức năng upload chỉ triển khai khi làm admin product management.

Luồng:

1. Admin chọn file.
2. Server validation MIME type và dung lượng.
3. Server tạo tên file mới.
4. Server lưu file.
5. Server lưu đường dẫn tương đối vào `product_images`.
6. Admin chọn ảnh chính và thứ tự gallery.

Vị trí đề xuất:

    storage/app/public/products/{product_id}/

Không dùng trực tiếp tên file do người dùng gửi lên.

## 5. Quy chuẩn file

### 5.1. Tỷ lệ

Ảnh sản phẩm chính dùng tỷ lệ:

    1:1

Kích thước nguồn gợi ý:

- 800 x 800 px.
- 1000 x 1000 px.

### 5.2. Định dạng

Ưu tiên:

1. WebP.
2. JPEG.
3. PNG khi cần nền trong suốt.

SVG chỉ dùng cho placeholder hoặc illustration do dự án tự tạo.

### 5.3. Dung lượng

Mục tiêu:

- Thumbnail hoặc ảnh card: dưới 150 KB.
- Ảnh chi tiết: dưới 400 KB.

MVP không cần cài package xử lý ảnh tự động. Có thể tối ưu ảnh trước khi đưa vào seed data.

## 6. Quy tắc hiển thị

Thứ tự fallback:

1. Ảnh có `is_primary = true`.
2. Ảnh đầu tiên theo `sort_order`.
3. Placeholder cục bộ.

Product card:

- Dùng container vuông.
- Dùng `object-contain`.
- Có `loading="lazy"`.
- Có width và height hoặc aspect ratio ổn định.
- Có alt text có ý nghĩa.
- Không query database trong Blade.

Trang chi tiết:

- Ảnh chính không lazy load nếu nằm phía trên fold.
- Ảnh gallery còn lại có thể lazy load.
- Không cắt mất thân sản phẩm.

## 7. Đường dẫn và URL

Database chỉ lưu path tương đối:

    products/15/01JABCXYZ.webp

Ứng dụng tạo URL bằng storage helper.

Không lưu:

    http://localhost:8000/storage/products/15/01JABCXYZ.webp

Lý do:

- Dễ đổi domain.
- Dễ chuyển môi trường.
- Dễ test.
- Không gắn dữ liệu với localhost.

## 8. Model relationship

`Product` có:

- `images()`.
- Có thể có `primaryImage()` nếu triển khai rõ ràng và không gây query khó hiểu.

Trang danh sách phải eager load dữ liệu ảnh cần thiết.

Không tạo accessor tự phát sinh query ở mỗi product card.

## 9. Alt text

Alt text nên mô tả nội dung ảnh:

    iPhone 16 Pro màu Titan sa mạc

Không dùng:

    image
    product image
    iphone

Nếu ảnh chỉ mang tính trang trí, dùng alt rỗng theo đúng ngữ cảnh.

## 10. Bảo mật upload

Validation tối thiểu:

- File thật sự là ảnh.
- MIME hợp lệ.
- Extension hợp lệ.
- Kích thước file giới hạn.
- Kích thước ảnh hợp lý nếu cần.
- Không cho SVG upload từ admin trong MVP.
- Không cho file thực thi.
- Tên file sinh bởi server.
- Không lưu file vào thư mục có thể thực thi PHP.

Định dạng upload cho admin nên giới hạn:

- JPEG.
- PNG.
- WebP.

## 11. Seeder và repository

Không bắt buộc commit nhiều ảnh thật vào Git.

Repository nên có:

- Placeholder SVG.
- Một vài ảnh demo nhẹ nếu có quyền sử dụng.
- Seeder hoạt động khi ảnh demo không tồn tại bằng cách fallback về placeholder.

Không để seeder thất bại chỉ vì thiếu file ảnh demo.

## 12. Tiêu chí nghiệm thu

- Product không ảnh vẫn hiển thị đúng.
- Product có primary image dùng primary image.
- Product có ảnh nhưng không có primary dùng ảnh đầu tiên.
- Product listing không có N+1 do hình ảnh.
- Ảnh card không làm layout shift rõ rệt.
- Không có external hotlink.
- Upload admin từ chối file không hợp lệ.
- Database chỉ lưu relative path.

## 13. Placeholder theo danh mục

MVP chỉ bắt buộc một placeholder chung.

Sau này có thể bổ sung placeholder cho iPhone, iPad và phụ kiện nếu thực sự giúp UX. Cursor không tự tạo thêm trước khi có nhu cầu rõ ràng.
