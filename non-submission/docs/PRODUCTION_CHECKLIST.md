# Production checklist — iStore

Danh sách rà soát trước khi triển khai môi trường thật (hoặc demo công khai). Dự án học tập — không phải cửa hàng Apple chính thức.

## Ứng dụng

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` trỏ đúng domain HTTPS
- [ ] `php artisan key:generate` đã chạy (key duy nhất, không dùng key mẫu)
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan storage:link` (ảnh sản phẩm trong `storage/app/public`)

## Database

- [ ] MySQL user riêng, quyền tối thiểu (không dùng `root` không mật khẩu)
- [ ] Backup định kỳ
- [ ] Migration đã chạy: `php artisan migrate --force`
- [ ] Seed chỉ khi cần demo — **không** seed tài khoản `password` trên production thật

## Bảo mật

- [ ] HTTPS bắt buộc (reverse proxy / Laragon / hosting)
- [ ] Session cookie `secure` khi dùng HTTPS (`SESSION_SECURE_COOKIE=true`)
- [ ] Không commit `.env`
- [ ] Admin route chỉ cho user `role=admin` (`EnsureUserIsAdmin`)
- [ ] Upload ảnh: chỉ `jpg/jpeg/png/webp`, tối đa 4MB (`ProductImageStoreRequest`)
- [ ] Ảnh sản phẩm lưu local — `ProductImageUrl` từ chối URL `http(s)://` (không hotlink)
- [ ] Đăng nhập tái tạo session (`LoginController`)

## Store / nghiệp vụ

- [ ] `SHIPPING_FEE`, `SHIPPING_FREE_THRESHOLD` phù hợp (`config/store.php`)
- [ ] `LOW_STOCK_THRESHOLD` cho cảnh báo tồn kho dashboard
- [ ] Kiểm tra luồng đặt hàng COD và hoàn tồn kho khi hủy đơn

## Frontend & assets

- [ ] `npm run build` — không dùng Vite dev server trên production
- [ ] Placeholder ảnh: `public/images/placeholders/product-placeholder.svg`
- [ ] Kiểm tra responsive trên mobile (nav, giỏ, checkout, admin sidebar)

## Kiểm tra cuối

```powershell
php artisan test
npm run build
```

Kỳ vọng: toàn bộ test pass, build không lỗi.

## Liên quan

- [`docs/CHECKLIST.md`](CHECKLIST.md) — tiến độ phase
- [`docs/TECHDEBT.md`](TECHDEBT.md) — UX đã rollback (auto cart sync, checkout summary)
- [`README.md`](../README.md) — hướng dẫn cài đặt
