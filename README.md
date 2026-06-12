# iStore — Apple Store Web App

Website bán iPhone, iPad và phụ kiện (đồ án học tập): **Laravel 13**, **Blade SSR**, **Tailwind CSS 4**, **jQuery**, **MySQL**.

---

## Bắt đầu nhanh

| Môi trường | Khuyến nghị | Hướng dẫn |
| --- | --- | --- |
| **Laragon** (Windows) | Phát triển hàng ngày | [Cài Laragon](#cài-đặt-laragon-windows) |
| **XAMPP** (Windows) | Nộp bài / máy chỉ có XAMPP | [XAMPP.md](XAMPP.md) — copy + import SQL |
| **macOS** | Dev trên Mac | [Cài macOS](#cài-đặt-macos) |
| **Docker / Podman** | Demo production-like | [Docker / Podman](#docker--podman-tùy-chọn) |

**Tài khoản demo** (sau seed hoặc import SQL):

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin | `admin@istore.test` | `password` |
| Khách | `customer1@istore.test` … `customer5@istore.test` | `password` |

---

## Giới thiệu

### Mục đích

iStore mô phỏng cửa hàng Apple quy mô nhỏ: khách tìm kiếm/lọc sản phẩm, chọn biến thể (màu, dung lượng), giỏ hàng AJAX, đặt hàng COD, theo dõi đơn. Admin quản lý catalog, đơn hàng, khách hàng và dashboard thống kê.

### Công nghệ

| Thành phần | Phiên bản |
| --- | --- |
| PHP | ^8.3 |
| Laravel | 13.x |
| MySQL | 8.x |
| Tailwind CSS | 4.x (Vite) |
| jQuery | 4.x |
| Node.js | 22.x (chỉ cần khi build frontend) |

Không dùng React, Vue, Livewire, Inertia hay Alpine.

### Chức năng chính

**Khách:** đăng ký/đăng nhập, tìm kiếm & lọc AJAX, chi tiết sản phẩm (gallery, đổi màu/dung lượng), giỏ hàng, checkout COD, lịch sử đơn.

**Admin:** dashboard, CRUD danh mục/dòng/màu/dung lượng/sản phẩm/ảnh/biến thể, quản lý đơn (đổi trạng thái, hủy + hoàn kho), khách hàng.

### Kịch bản trình bày demo

Thư mục `demo/` có resource sản phẩm mẫu và file `demo/demo.md` (kịch bản ~20 phút).

---

## Yêu cầu chung

- PHP **8.3+** với extension: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`
- Composer 2.x
- MySQL 8.x
- Node.js 22.x + npm (chỉ máy **build** frontend; người cài XAMPP theo [XAMPP.md](XAMPP.md) có thể bỏ qua nếu đã có `public/build/`)

---

## Cài đặt Laragon (Windows)

Khuyến nghị cho phát triển. Document root trỏ `public/` — hostname mặc định:

```
http://apple-store-web-app.test
```

### Các bước

```powershell
cd C:\laragon\www\apple-store-web-app

composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

Chỉnh `.env`:

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

Laragon → **Start All** → mở `http://apple-store-web-app.test`.

### Lệnh thường dùng

```powershell
php artisan migrate:fresh --seed    # reset DB + seed
php artisan test                    # chạy test
npm run dev                         # Vite khi sửa CSS/JS
```

### Lỗi thường gặp (Laragon)

| Triệu chứng | Cách xử lý |
| --- | --- |
| Ảnh `/storage/...` 404 | `php artisan storage:link` |
| `Vite manifest not found` | `npm run build` hoặc `npm run dev` |
| Hostname `.test` không mở | Laragon → Menu → Reload |
| CSS không load | `APP_URL` khớp URL trình duyệt |

---

## Cài đặt macOS

Yêu cầu: Homebrew, PHP 8.3+, MySQL, Node.js 22.x.

```bash
cd ~/Sites/apple-store-web-app
composer install && npm install
cp .env.example .env && php artisan key:generate
```

`.env` mẫu:

```dotenv
APP_URL=http://127.0.0.1:8000
DB_DATABASE=apple_store
DB_USERNAME=root
DB_PASSWORD=
```

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
php artisan storage:link
npm run build
```

Chạy (hai terminal):

```bash
php artisan serve          # http://127.0.0.1:8000
npm run dev                # khi sửa frontend
```

---

## XAMPP (Windows)

**Cách đơn giản nhất** — không cần `npm`/`migrate` trên máy người chấm nếu nhóm đã chuẩn bị gói:

1. Nhóm dev chạy: `.\scripts\prepare-xampp.ps1`
2. Người cài: copy vào `C:\xampp\htdocs\apple-store-web-app` → `.env` → import `database/dumps/apple_store-demo.sql` → `storage:link`

Chi tiết từng bước, virtual host và xử lý lỗi: **[XAMPP.md](XAMPP.md)**

---

## Docker / Podman (tùy chọn)

Dùng cho demo production-like, không khuyến nghị dev hàng ngày trên Windows (chậm do bind mount).

| File | Mục đích |
| --- | --- |
| `.env.docker` | DB host `mysql` trong container |
| `.env` | Laragon / host (`127.0.0.1`) |

```powershell
Copy-Item .env.docker.example .env.docker
podman compose up -d --build
podman compose exec app php artisan migrate --seed
```

URL: [http://localhost:8080](http://localhost:8080) · phpMyAdmin: [http://localhost:8081](http://localhost:8081) (`apple_store` / `secret`)

Reset DB:

```powershell
podman compose exec app php artisan migrate:fresh --seed --force
```

(Dùng `docker` thay `podman` nếu cần.)

**Lưu ý:** Sau khi chạy container, `public/storage` trên Windows có thể bị ghi đè — trên Laragon chạy lại:

```powershell
Remove-Item public\storage -Force -ErrorAction SilentlyContinue
php artisan storage:link
```

---

## Kiểm tra chất lượng

```bash
php artisan test
npm run build
```

Kỳ vọng: toàn bộ test pass, build Vite thành công.

---

## Lưu ý chung

- File `.env` không commit (trừ khi nộp bài demo có `APP_KEY` cố định — xem [XAMPP.md](XAMPP.md)).
- Test PHPUnit dùng SQLite in-memory; dev dùng MySQL.
- Ảnh demo: `storage/app/public/products/demo/` — cần `php artisan storage:link`.
- Dự án học tập, không phải cửa hàng Apple chính thức.
- Thư mục `non-submission/` chứa tài liệu AI/Cursor — **không nộp** cùng bài.
