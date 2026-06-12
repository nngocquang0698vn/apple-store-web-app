# Cài đặt trên XAMPP (Windows)

Hướng dẫn **đơn giản nhất**: copy project vào `htdocs`, import SQL có sẵn, chạy vài lệnh — **không cần** `npm run build` hay `migrate` trên máy người cài (nếu nhóm đã chuẩn bị gói trước).

> Yêu cầu: **PHP 8.3+**, MySQL 8.x, Apache. Kiểm tra: `C:\xampp\php\php.exe -v`

---

## Ai làm gì?

| Vai trò | Việc cần làm |
| --- | --- |
| **Nhóm phát triển** (một lần) | Chạy `scripts/prepare-xampp.ps1` trên máy Laragon/XAMPP → có SQL dump + frontend build sẵn |
| **Người cài / thầy chấm** | Copy thư mục → `.env` → import SQL → `storage:link` → mở trình duyệt |

---

## Phần A — Nhóm dev: chuẩn bị gói nộp (chạy một lần)

Trên máy đã cài Laragon hoặc XAMPP + Composer + Node.js:

```powershell
cd C:\laragon\www\apple-store-web-app   # hoặc đường dẫn project

# Cài dependency PHP (nếu chưa)
composer install

# Build frontend + seed DB + export SQL + storage:link
.\scripts\prepare-xampp.ps1
```

Script sẽ:

1. `npm ci` và `npm run build` → tạo `public/build/`
2. `php artisan migrate:fresh --seed` → dữ liệu demo đầy đủ
3. `php artisan storage:link` → symlink ảnh demo
4. Export `database/dumps/apple_store-demo.sql`

**Khi nộp bài**, đảm bảo zip/repo có:

| Thư mục / file | Bắt buộc |
| --- | --- |
| `vendor/` | Có (chạy `composer install` trước) |
| `public/build/` | Có (sau `prepare-xampp.ps1`) |
| `database/dumps/apple_store-demo.sql` | Có (sau script) |
| `storage/app/public/products/demo/` | Có (ảnh sản phẩm) |
| `.env.xampp.example` | Có |
| `node_modules/` | **Không** cần (đã build xong) |

Tuỳ chọn: copy `.env.xampp.example` thành `.env` và chạy `php artisan key:generate` **một lần**, rồi nộp luôn file `.env` (chỉ dùng cho demo học tập).

---

## Phần B — Người cài: 5 bước trên XAMPP

### Bước 1 — Copy project

Giải nén / copy toàn bộ vào:

```
C:\xampp\htdocs\apple-store-web-app
```

Cấu trúc phải có `public\index.php`, `vendor/`, `public/build/`.

### Bước 2 — Virtual host (khuyến nghị, URL gọn)

1. Mở `C:\xampp\apache\conf\extra\httpd-vhosts.conf`, thêm:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/apple-store-web-app/public"
    ServerName apple-store.local
    <Directory "C:/xampp/htdocs/apple-store-web-app/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. Mở `C:\Windows\System32\drivers\etc\hosts` (Run as Administrator), thêm:

```
127.0.0.1   apple-store.local
```

3. Trong `C:\xampp\apache\conf\httpd.conf`:
   - Bỏ comment `LoadModule rewrite_module modules/mod_rewrite.so`
   - Đảm bảo có dòng `Include conf/extra/httpd-vhosts.conf`

4. Khởi động lại **Apache** từ XAMPP Control Panel.

**URL truy cập:** [http://apple-store.local](http://apple-store.local)

> **Không cấu hình virtual host?** Dùng [http://localhost/apple-store-web-app/public](http://localhost/apple-store-web-app/public) và đặt `APP_URL` tương ứng trong `.env`.

### Bước 3 — File `.env`

```powershell
cd C:\xampp\htdocs\apple-store-web-app
Copy-Item .env.xampp.example .env
C:\xampp\php\php.exe artisan key:generate
```

Nếu nhóm đã nộp sẵn `.env` có `APP_KEY`, bỏ qua `key:generate`.

Chỉnh `.env` nếu MySQL root có mật khẩu:

```dotenv
DB_PASSWORD=mat_khau_cua_ban
APP_URL=http://apple-store.local
```

### Bước 4 — Import database

1. XAMPP Control Panel → **Start** Apache và MySQL.
2. Mở [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Tạo database `apple_store`, collation `utf8mb4_unicode_ci` (nếu chưa có).
4. Chọn database → tab **Import** → chọn file:

```
database/dumps/apple_store-demo.sql
```

**Hoặc** dòng lệnh (nếu `mysql` có trong PATH):

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
C:\xampp\mysql\bin\mysql.exe -u root apple_store < database\dumps\apple_store-demo.sql
```

> Không chạy `php artisan migrate` nếu đã import SQL — dữ liệu đã đủ.

### Bước 5 — Liên kết ảnh storage

```powershell
cd C:\xampp\htdocs\apple-store-web-app
C:\xampp\php\php.exe artisan storage:link
```

Mở trình duyệt → trang chủ → kiểm tra ảnh sản phẩm (không còn placeholder toàn bộ).

---

## Kiểm tra nhanh

| Trang | URL (virtual host) |
| --- | --- |
| Trang chủ | http://apple-store.local |
| Sản phẩm | http://apple-store.local/products |
| Admin | http://apple-store.local/admin |

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin | `admin@istore.test` | `password` |
| Khách | `customer1@istore.test` | `password` |

---

## Lỗi thường gặp

| Triệu chứng | Cách xử lý |
| --- | --- |
| 404 mọi route trừ `/` | Bật `mod_rewrite`, `AllowOverride All`, document root = `public/` |
| `Vite manifest not found` | Thiếu `public/build/` — nhóm dev chạy lại `.\scripts\prepare-xampp.ps1` |
| Ảnh `/storage/...` 404 | Chạy `php artisan storage:link` |
| Lỗi kết nối DB | MySQL đã Start; `DB_*` trong `.env` đúng |
| CSS/JS không load | `APP_URL` khớp URL trên thanh địa chỉ |
| PHP quá cũ | Cần PHP **8.3+** — nâng XAMPP hoặc dùng Laragon |

---

## Dự phòng: không dùng Apache

```powershell
cd C:\xampp\htdocs\apple-store-web-app
# Sửa .env: APP_URL=http://127.0.0.1:8000
C:\xampp\php\php.exe artisan serve
```

Mở [http://127.0.0.1:8000](http://127.0.0.1:8000). Frontend vẫn dùng file đã build trong `public/build/` (không cần `npm run dev`).

---

## Tóm tắt một dòng

**Dev:** `.\scripts\prepare-xampp.ps1` → **Người cài:** copy vào `htdocs` → `.env` → import `apple_store-demo.sql` → `storage:link` → mở `http://apple-store.local`.
