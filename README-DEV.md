# iStore — Hướng dẫn phát triển (Laragon)

Tài liệu dành **sinh viên** phát triển trên Windows với [Laragon](https://laragon.org/).  
Hướng dẫn chấm bài / XAMPP-Lite: [README.md](README.md).

---

## Yêu cầu

- [Laragon](https://laragon.org/) (PHP **8.3+**, MySQL, Composer trong PATH)
- Node.js **22.x** + npm (build frontend)
- Git

---

## Cài đặt lần đầu

```powershell
cd C:\laragon\www\apple-store-web-app

composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

`.env` mẫu (Laragon tự tạo hostname `.test`):

```dotenv
APP_URL=http://apple-store-web-app.test
DB_DATABASE=apple_store
DB_USERNAME=root
DB_PASSWORD=
```

Database và chạy lần đầu:

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
php artisan storage:link
npm run build
```

Laragon → **Start All** → mở **http://apple-store-web-app.test**

> Laragon tự thêm `apple-store-web-app.test` vào file `hosts` — không cần sửa thủ công.

---

## URL dev

| Trang | URL |
| --- | --- |
| Trang chủ | http://apple-store-web-app.test |
| Admin app | http://apple-store-web-app.test/admin |
| Sản phẩm | http://apple-store-web-app.test/products |

**Demo:** Admin `admin@istore.test` / `password`

---

## Lệnh thường dùng

```powershell
php artisan migrate:fresh --seed   # reset DB + seed
php artisan test                   # chạy test
npm run dev                        # Vite khi sửa CSS/JS
npm run build                      # build production assets
```

---

## Chuẩn bị gói nộp (XAMPP-Lite)

Trên máy dev, sau khi code ổn:

```powershell
.\scripts\prepare-xampp.ps1
.\scripts\copy-release.ps1
```

Nén thủ công `apple-store-web-app-release` → **`apple-store-web-app-release.zip`** rồi nộp (xem README.md).

Gói `apple-store-web-app-release` dùng `.env` với `APP_URL=http://127.0.0.1:8080` — **khác** `.env` dev Laragon; đó là cố ý (xem README.md).

Deploy thử trên XAMPP local:

```powershell
.\scripts\copy-to-xampp-lite-www.ps1
.\scripts\copy-xampp-lite-apache-conf.ps1 -XamppRoot "C:\xampp_lite_8_3" -Verbose
```

> Trên XAMPP-Lite: luôn mở app bằng **`http://127.0.0.1:8080`**, không dùng `localhost:8080` (tránh CORS).

---

## So sánh môi trường

| | Laragon (dev) | XAMPP-Lite (nộp/chấm) | Docker |
| --- | --- | --- | --- |
| URL app | `http://apple-store-web-app.test` | `http://127.0.0.1:8080` | `http://localhost:8080` |
| Sửa `hosts` | Laragon tự lo | Không | Không |
| File env | `.env.example` | `.env.xampp.example` | `.env.docker.example` |

---

## Lỗi thường gặp (Laragon)

| Triệu chứng | Cách xử lý |
| --- | --- |
| `.test` không mở | Laragon → Menu → Reload; Start All |
| Ảnh `/storage/` 404 | `php artisan storage:link` |
| `Vite manifest not found` | `npm run build` hoặc `npm run dev` |
| CSS không load | `APP_URL` khớp URL trình duyệt |
| PHP sai phiên bản | Laragon → PHP → 8.3.x; terminal dùng PHP của Laragon |

---

## macOS (tuỳ chọn)

Homebrew, PHP 8.3+, MySQL, Node.js 22.x:

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed && php artisan storage:link && npm run build
php artisan serve   # http://127.0.0.1:8000
```

Sửa `APP_URL=http://127.0.0.1:8000` trong `.env`.

---

## Kiểm tra chất lượng

```bash
php artisan test
npm run build
```

Kỳ vọng: toàn bộ test pass, build Vite thành công.
