# iStore — Apple Store Web App

Website bán điện thoại Apple (đồ án học tập), xây dựng bằng Laravel Blade + Tailwind CSS + jQuery.

**Trạng thái:** Phase 0 hoàn thành — layout khách hàng/admin, trang chủ và dashboard placeholder.  
**Task tiếp theo:** Task 0.2 trong [`docs/TASKS.md`](docs/TASKS.md) (enum, admin middleware, Pint).

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

| Trang | URL |
|---|---|
| Trang chủ | http://apple-store-web-app.test |
| Admin | http://apple-store-web-app.test/admin |

Document root trỏ vào `public/` — không cần cấu hình thêm.

### Phát triển giao diện

Mở terminal riêng và giữ chạy:

```powershell
npm run dev
```

### Dự phòng (`artisan serve`)

```powershell
php artisan serve
npm run dev
```

Mở http://127.0.0.1:8000 — đặt `APP_URL=http://127.0.0.1:8000` nếu dùng lâu dài.

---

## Kiểm tra chất lượng

```powershell
php artisan about
php artisan route:list
php artisan test
npm run build
```

Kỳ vọng: **4 tests passed**, build thành công. Chạy `npm run build` (hoặc `npm run dev`) trước khi test nếu chưa có `public/build/manifest.json`.

---

## Routes hiện tại

| Method | URI | Name |
|---|---|---|
| GET | `/` | `home` |
| GET | `/admin` | `admin.dashboard` |

Chưa có middleware auth/admin.

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
