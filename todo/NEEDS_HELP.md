# Cần hỗ trợ: ảnh sản phẩm demo

Tài liệu này dành cho người giúp đỡ **tìm hoặc chuẩn bị ảnh sản phẩm** cho dự án iStore (đồ án học tập). Chi tiết kỹ thuật tham chiếu [`docs/IMAGE_STRATEGY.md`](../docs/IMAGE_STRATEGY.md).

**Database hiện tại:** xem file [`script.sql`](script.sql) trong cùng thư mục (export MySQL `apple_store`, ngày export gần nhất khi file được tạo).

---

## 1. Bối cảnh

- Website đã có **13 sản phẩm** (iPhone, iPad, iPod, phụ kiện sạc) trong database.
- Bảng `product_images` hiện **chưa có dữ liệu** → mọi sản phẩm đang dùng **placeholder SVG** tại `public/images/placeholders/product-placeholder.svg`.
- Mục tiêu: thêm **ảnh demo cục bộ** (giai đoạn 2 trong IMAGE_STRATEGY) để trang danh sách và chi tiết sản phẩm trông thật hơn.
- **Không** dán link ảnh từ website ngoài vào code hoặc database.

---

## 2. Cần tìm ảnh như thế nào?

### 2.1. Nội dung ảnh

| Yêu cầu | Chi tiết |
|---------|----------|
| Chủ thể | Máy **nhìn thẳng / hơi nghiêng nhẹ**, nền **trắng hoặc xám rất nhạt** |
| Tỷ lệ | **Vuông 1:1** (bắt buộc) |
| Kích thước nguồn | **800×800** hoặc **1000×1000** pixel |
| Định dạng | Ưu tiên **WebP**, hoặc **JPEG** chất lượng cao |
| Dung lượng | Card: **&lt; 150 KB**; ảnh chi tiết: **&lt; 400 KB** (nén trước khi gửi) |
| Màu sắc | Khớp **màu biến thể** nếu có ảnh riêng theo màu (xem bảng §4) |
| Phụ kiện | Adapter/cáp: ảnh sản phẩm đơn, nền sạch |

### 2.2. Ảnh **không** dùng

- Logo Apple lớn làm chủ đề, watermark thương hiệu bên thứ ba.
- Ảnh lifestyle phức tạp, cắt méo, viền đen, tỷ lệ dọc.
- Ảnh copy từ trang thương mại nếu **không chắc** được phép dùng cho đồ án.
- Screenshot, render mờ, ảnh pixelated.

### 2.3. Nếu không có ảnh thật

Chấp nhận **ảnh minh họa** (mockup điện thoại/tablet generic, không logo Apple) — vẫn đặt tên file đúng quy tắc bên dưới.

---

## 3. Đặt file ở đâu?

### 3.1. Thư mục trên máy dev (sau khi clone repo)

```
storage/app/public/products/demo/
```

Ví dụ đường dẫn đầy đủ:

```
apple-store-web-app/storage/app/public/products/demo/iphone-16-black.webp
```

### 3.2. Lệnh cần chạy (người nhận ảnh)

```bash
php artisan storage:link
```

Ảnh sẽ truy cập qua URL dạng `/storage/products/demo/...`.

### 3.3. Database chỉ lưu path tương đối

Đúng:

```
products/demo/iphone-16-black.webp
```

Sai (không lưu):

```
http://localhost/storage/products/demo/iphone-16-black.webp
C:\laragon\www\...\iphone-16-black.webp
```

### 3.4. Cách giao ảnh cho team

Gửi **folder `demo/`** (hoặc file `.zip` giải nén ra đúng cấu trúc trên), **không** gửi chỉ link Google Drive hotlink vào app.

---

## 4. Quy tắc đặt tên file

### 4.1. Công thức chung

```
{product-slug}-{color-slug}.webp
```

- `product-slug`: slug sản phẩm trong database (chữ thường, nối bằng `-`).
- `color-slug`: slug màu trong database (xem §4.3).
- Chỉ dùng chữ thường, số, dấu `-`.
- Extension: `.webp` (hoặc `.jpg` nếu không convert được WebP).

### 4.2. Sản phẩm chỉ cần **một ảnh chính**

Phụ kiện / sản phẩm một màu — bỏ phần màu hoặc dùng màu mặc định:

```
{product-slug}.webp
```

hoặc

```
{product-slug}-white.webp
```

### 4.3. Slug màu trong database

| Slug | Tên hiển thị |
|------|----------------|
| `black` | Đen |
| `white` | Trắng |
| `blue` | Xanh dương |
| `pink` | Hồng |
| `purple` | Tím |
| `green` | Xanh lá |
| `natural-titanium` | Titanium tự nhiên |
| `black-titanium` | Titanium đen |
| `white-titanium` | Titanium trắng |
| `desert-titanium` | Titanium xanh sa mạc |

---

## 5. Danh sách ảnh cần có

### 5.1. Mức tối thiểu (13 ảnh — một ảnh / sản phẩm)

| # | Sản phẩm | `product-slug` | Tên file gợi ý |
|---|----------|----------------|----------------|
| 1 | iPhone 15 | `iphone-15` | `iphone-15-black.webp` |
| 2 | iPhone 15 Pro | `iphone-15-pro` | `iphone-15-pro-natural-titanium.webp` |
| 3 | iPhone 16 | `iphone-16` | `iphone-16-black.webp` |
| 4 | iPhone 16 Pro | `iphone-16-pro` | `iphone-16-pro-black-titanium.webp` |
| 5 | iPhone 16 Pro Max | `iphone-16-pro-max` | `iphone-16-pro-max-black-titanium.webp` |
| 6 | iPad 10.9 inch | `ipad-10-9` | `ipad-10-9-blue.webp` |
| 7 | iPad Air M2 | `ipad-air-m2` | `ipad-air-m2-blue.webp` |
| 8 | iPad Pro 11 inch M4 | `ipad-pro-11-m4` | `ipad-pro-11-m4-black.webp` |
| 9 | iPod touch (thế hệ 7) | `ipod-touch-gen-7` | `ipod-touch-gen-7-blue.webp` |
| 10 | Apple 20W USB-C | `apple-20w-usb-c-adapter` | `apple-20w-usb-c-adapter.webp` |
| 11 | Apple 30W USB-C | `apple-30w-usb-c-adapter` | `apple-30w-usb-c-adapter.webp` |
| 12 | Cáp USB-C sang Lightning 1m | `usb-c-to-lightning-1m` | `usb-c-to-lightning-1m.webp` |
| 13 | Cáp USB-C 1m | `usb-c-cable-1m` | `usb-c-cable-1m.webp` |

### 5.2. Mức tốt hơn (ảnh theo màu — iPhone)

Mỗi dòng iPhone trong seed có các màu: `black`, `blue`, `pink`, `natural-titanium`, `black-titanium`.

Ví dụ cho `iphone-16`:

```
iphone-16-black.webp
iphone-16-blue.webp
iphone-16-pink.webp
iphone-16-natural-titanium.webp
iphone-16-black-titanium.webp
```

Lặp tương tự cho: `iphone-15`, `iphone-15-pro`, `iphone-16-pro`, `iphone-16-pro-max`.

### 5.3. Mức tốt hơn (iPad)

Màu: `black`, `white`, `blue` cho mỗi: `ipad-10-9`, `ipad-air-m2`, `ipad-pro-11-m4`.

---

## 6. Alt text (mô tả ảnh)

Khi ghi chú kèm ảnh, dùng tiếng Việt có nghĩa, ví dụ:

- `iPhone 16 Pro màu Titanium đen`
- `iPad Air M2 màu xanh dương`
- `Củ sạc Apple 20W USB-C`

Không dùng: `image`, `product`, `iphone`.

---

## 7. Checklist trước khi bàn giao

- [ ] Ảnh vuông 1:1, nền sạch, đủ sáng.
- [ ] Đã nén (WebP/JPEG trong giới hạn dung lượng).
- [ ] Tên file đúng slug, chữ thường, không dấu, không khoảng trắng.
- [ ] Đặt trong `storage/app/public/products/demo/`.
- [ ] Không gửi URL hotlink thay cho file.
- [ ] (Tuỳ chọn) Kèm bảng CSV: `filename`, `product-slug`, `color-slug`, `alt-text`, `is-primary` (yes/no).

---

## 8. Sau khi có ảnh — việc dev sẽ làm

1. Copy ảnh vào `storage/app/public/products/demo/`.
2. Chạy `php artisan storage:link` (nếu chưa có symlink).
3. Cập nhật seeder hoặc insert `product_images` với `path` = `products/demo/{filename}`.
4. Đánh dấu một ảnh `is_primary = true` mỗi sản phẩm.

Seeder **không được fail** nếu thiếu ảnh — thiếu ảnh vẫn fallback placeholder.

---

## 9. Tài liệu liên quan

| File | Nội dung |
|------|----------|
| [`docs/IMAGE_STRATEGY.md`](../docs/IMAGE_STRATEGY.md) | Chiến lược đầy đủ (placeholder → demo → admin upload) |
| [`todo/script.sql`](script.sql) | Dump database `apple_store` (products, variants, orders, …) |
| [`docs/CHECKLIST.md`](../docs/CHECKLIST.md) | Tiến độ phase dự án |

---

## 10. Câu hỏi thường gặp

**Hỏi: Có bắt buộc đúng model Apple không?**  
Đáp: Ưu tiên đúng model trong bảng §5.1; nếu không có, dùng mockup tương tự (smartphone/tablet generic).

**Hỏi: Có cần ảnh cho từng dung lượng 128/256/512 GB không?**  
Đáp: **Không.** Chỉ cần ảnh theo **sản phẩm** hoặc **màu**, không theo dung lượng.

**Hỏi: Ảnh gallery nhiều góc có cần không?**  
Đáp: MVP chỉ cần **1 ảnh chính / sản phẩm** (hoặc / màu). Gallery thêm sau.

**Hỏi: Import `script.sql` thế nào?**  
Đáp: Trên Laragon/MySQL: tạo database `apple_store` (nếu chưa có), rồi import file bằng HeidiSQL hoặc `mysql -u root apple_store < todo/script.sql`.
