# Demo kit — iStore

Thư mục này phục vụ **buổi trình bày đồ án** (~20 phút). Kịch bản chi tiết nằm trong [`demo.md`](demo.md).

## Cấu trúc

```
demo/
├── demo.md                 # Kịch bản trình bày + checklist
├── products/
│   ├── iphone-17-pro/      # Sản phẩm demo 1
│   ├── iphone-17/          # Sản phẩm demo 2
│   └── iphone-air/         # Sản phẩm demo 3
└── README.md
```

Mỗi sản phẩm gồm:

| File | Dùng để |
| --- | --- |
| `product.json` | Metadata, gợi ý SKU/giá biến thể |
| `short_description.txt` | Dán vào ô **Mô tả ngắn** |
| `description.html` | Dán vào **Mô tả chi tiết** (Quill) |
| `specifications.txt` | Dán vào ô **Thông số** |
| `images/*.webp` | Upload gallery admin |
| `images/manifest.json` | Thứ tự ảnh + ảnh chính |

## Tải lại ảnh từ Cellphones

```powershell
cd C:\laragon\www\apple-store-web-app
php scripts/fetch-demo-resources.php
php scripts/fetch-demo-resources.php iphone-17-pro -v   # một sản phẩm
```

Nguồn tham khảo (chỉ dùng cho demo học tập):

- https://cellphones.com.vn/iphone-17-pro.html
- https://cellphones.com.vn/iphone-17-256gb.html
- https://cellphones.com.vn/iphone-air-256gb.html

## Gợi ý nhanh khi thêm sản phẩm demo

1. Admin → **Dòng sản phẩm** → tạo `iPhone 17 Series` (năm 2025) nếu chưa có.
2. Admin → **Màu sắc** → thêm các màu trong `product.json` → `suggested_variants[].color_label`.
3. Admin → **Sản phẩm** → **Thêm sản phẩm** → dán nội dung từ thư mục tương ứng.
4. Trang chi tiết sản phẩm → upload ảnh từ `images/` (ảnh `primary: true` trong manifest đặt làm chính).
5. **Biến thể** → nhập SKU/giá/tồn kho theo `suggested_variants` trong `product.json`.
