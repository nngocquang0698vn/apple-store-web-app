# iStore — Apple Store Web App

Website bán điện thoại Apple (đồ án học tập), xây dựng bằng Laravel Blade + Tailwind CSS + jQuery.

**Trạng thái:** Phase 0 hoàn thành — layout khách hàng/admin, coding foundation, enum, admin middleware và Pint.
**Task tiếp theo:** Task 1.1 trong [`docs/TASKS.md`](docs/TASKS.md) (register).

---

## Stack

| Thành phần | Phiên bản |
|---|---|
| PHP | 8.3.x (Laragon) |
| Laravel | 13.x |
| MySQL | 8.x (Laragon) |
| Tailwind CSS | 4.x (Vite) |
| jQuery | 4.x |
| Node.js | 22.x |

Không dùng React, Vue, Livewire, Inertia, Alpine hay Bootstrap.

---

## Yêu cầu

- [Laragon](https://laragon.org/) (Apache/Nginx + MySQL + PHP 8.3)
- Composer 2.x
- Node.js 22.x và npm 10.x
- Git (tùy chọn)

Kiểm tra nhanh:

```powershell
php --version
composer --version
mysql --version
node --version
npm --version
```

---

## Cài đặt

Project đặt tại `C:\laragon\www\apple-store-web-app` (Laragon tự map hostname theo tên folder).

```powershell
cd C:\laragon\www\apple-store-web-app

composer install
npm install

Copy-Item .env.example .env
php artisan key:generate
```

Chỉnh `.env` (file local, **không commit**):

```dotenv
APP_NAME="iStore"
APP_URL=http://apple-store-web-app.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apple_store
DB_USERNAME=root
DB_PASSWORD=
```

Tạo database và chạy migration:

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
npm run build
```

Trong Laragon: **Start All** → **Menu → Reload** nếu hostname `.test` chưa hoạt động.

---

## Chạy project

### Laragon (khuyến nghị)

1. Laragon → **Start All** (Apache/Nginx + MySQL).
2. Mở site qua hostname Laragon:

| Trang | URL |
|---|---|
| Trang chủ | http://apple-store-web-app.test |
| Admin | http://apple-store-web-app.test/admin |

Document root trỏ vào `public/` — không cần `php artisan serve`.

### Vite dev server (khi sửa CSS/JS)

Dùng khi đang chỉnh `resources/css`, `resources/js` hoặc Blade có class Tailwind — Vite tự reload (HMR), không cần `npm run build` sau mỗi lần sửa.

Mở **terminal riêng**, giữ chạy:

```powershell
cd C:\laragon\www\apple-store-web-app
npm run dev
```

Kỳ vọng:

```
VITE v8.x  ready in ... ms
➜  Local:   http://localhost:5173/
➜  APP_URL: http://apple-store-web-app.test
```

- Truy cập website vẫn qua **Laragon** (`http://apple-store-web-app.test`), không mở `localhost:5173` trực tiếp.
- Dừng server: `Ctrl+C` trong terminal đang chạy `npm run dev`.

Chỉ xem/chạy app, không sửa giao diện:

```powershell
npm run build
```

Lệnh này tạo file tĩnh trong `public/build/` — đủ cho production hoặc khi không cần hot reload.

### Dự phòng (`artisan serve`)

Khi không dùng Laragon, chạy **hai terminal**:

```powershell
# Terminal 1 — Laravel
cd C:\laragon\www\apple-store-web-app
php artisan serve

# Terminal 2 — Vite
cd C:\laragon\www\apple-store-web-app
npm run dev
```

Mở http://127.0.0.1:8000 — đặt `APP_URL=http://127.0.0.1:8000` trong `.env` nếu dùng lâu dài.

Hoặc gộp một lệnh (serve + queue + log + Vite):

```powershell
composer dev
```

---

## Kiểm tra chất lượng

```powershell
php artisan about
php artisan route:list
php artisan test
npm run build
```

Kỳ vọng: **9 tests passed**, build thành công.

---

## Routes hiện tại

| Method | URI | Name | Middleware |
|---|---|---|---|
| GET | `/` | `home` | `web` |
| GET | `/admin` | `admin.dashboard` | `web`, `admin` |

Middleware `admin` chỉ cho user role `admin` đang `active` truy cập. Chưa có form login/register.

---

## Tài liệu dự án

| File | Mô tả |
|---|---|
| [`AGENTS.md`](AGENTS.md) | Quy tắc cho Cursor Agent |
| [`docs/PROJECT_CONTEXT.md`](docs/PROJECT_CONTEXT.md) | Trạng thái triển khai và quyết định kỹ thuật |
| [`docs/SPEC.md`](docs/SPEC.md) | Đặc tả chức năng |
| [`docs/TASKS.md`](docs/TASKS.md) | Kế hoạch theo phase |
| [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) | Kiến trúc mã nguồn |
| [`docs/DATABASE.md`](docs/DATABASE.md) | Thiết kế cơ sở dữ liệu |
| [`docs/ROUTES.md`](docs/ROUTES.md) | Danh sách route |
| [`docs/DEVELOPMENT_WINDOWS_LARAGON.md`](docs/DEVELOPMENT_WINDOWS_LARAGON.md) | Hướng dẫn Laragon chi tiết |
| [`docs/MOVE_TO_LARAGON_WWW.md`](docs/MOVE_TO_LARAGON_WWW.md) | Di chuyển project vào `www` |

Prompt gợi ý khi tiếp tục với Cursor:

```
@AGENTS.md
@docs/PROJECT_CONTEXT.md
@docs/TASKS.md
```

---

## Cấu trúc chính

```
app/Http/Controllers/
├── HomeController.php
└── Admin/DashboardController.php

resources/views/
├── layouts/          # app (khách) + admin
├── components/       # flash-message, product-image
├── home.blade.php
└── admin/dashboard.blade.php

routes/web.php
resources/css/app.css
resources/js/app.js
tests/Feature/
```

---

## Lưu ý

- `.env` nằm trong `.gitignore` — không commit.
- Test dùng SQLite in-memory (`phpunit.xml`); dev dùng MySQL.
- Ảnh sản phẩm: placeholder tại `public/images/placeholders/product-placeholder.svg`.
- Xử lý lỗi thường gặp: xem mục 10 trong [`docs/MOVE_TO_LARAGON_WWW.md`](docs/MOVE_TO_LARAGON_WWW.md).
