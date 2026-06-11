# Chạy dự án bằng Docker hoặc Podman

Cách nhanh nhất để tester chạy dự án mà không cần cài PHP/MySQL/Node trên máy host.

Hỗ trợ **cả hai**:

| Công cụ | Lệnh compose |
|---------|----------------|
| **Podman** (khuyến nghị nếu bạn đang dùng) | `podman compose` |
| **Docker** | `docker compose` |

File cấu hình: `compose.yaml` (và bản alias `docker-compose.yml`).

### Wrapper tự chọn runtime

Script sẽ ưu tiên **Podman**, nếu không có mới dùng **Docker**:

```bash
# Linux / macOS / Git Bash
./scripts/compose.sh up -d --build
./scripts/compose.sh exec app php artisan test
```

```powershell
# Windows PowerShell
.\scripts\compose.ps1 up -d --build
.\scripts\compose.ps1 exec app php artisan test
```

Trong phần dưới, `compose` có thể thay bằng:

- `podman compose`
- `docker compose`
- `./scripts/compose.sh` / `.\scripts\compose.ps1`

## Yêu cầu

- **Podman** 4.x+ với plugin Compose (`podman compose`), **hoặc**
- Docker Desktop / Docker Engine + Docker Compose v2

Kiểm tra:

```bash
podman compose version
# hoặc
docker compose version
```

Trên Windows với Podman: cài [Podman Desktop](https://podman-desktop.io/) và bật Podman machine trước khi chạy.

## Khởi động lần đầu

```bash
cp .env.docker.example .env
compose up -d --build
compose exec app php artisan key:generate
compose exec app php artisan migrate --seed
compose exec app php artisan storage:link
```

Ví dụ với Podman:

```bash
cp .env.docker.example .env
podman compose up -d --build
podman compose exec app php artisan migrate --seed
```

Ví dụ với Docker:

```bash
cp .env.docker.example .env
docker compose up -d --build
docker compose exec app php artisan migrate --seed
```

Trên Windows (PowerShell):

```powershell
Copy-Item .env.docker.example .env
.\scripts\compose.ps1 up -d --build
.\scripts\compose.ps1 exec app php artisan key:generate
.\scripts\compose.ps1 exec app php artisan migrate --seed
.\scripts\compose.ps1 exec app php artisan storage:link
```

> Tạo file `.env` **trước** `compose up` — file này được mount vào container.

Sau đó mở:

```text
http://localhost:8080
```

| Trang | URL |
|-------|-----|
| Trang chủ | http://localhost:8080 |
| Sản phẩm | http://localhost:8080/products |
| Admin | http://localhost:8080/admin |

Tài khoản demo sau seed: `admin@istore.test` / `password`

## Các lệnh thường dùng

Xem log:

```bash
compose logs -f app
```

Vào container app:

```bash
compose exec app bash
```

Chạy migrate:

```bash
compose exec app php artisan migrate
```

Chạy seed:

```bash
compose exec app php artisan db:seed
```

Chạy test:

```bash
compose exec app php artisan test
```

Dừng container nhưng **giữ dữ liệu MySQL**:

```bash
compose stop
```

Start lại và **vẫn giữ dữ liệu**:

```bash
compose start
```

Dừng và xóa container nhưng **vẫn giữ volume MySQL**:

```bash
compose down
```

Xóa toàn bộ container **và xóa luôn dữ liệu MySQL**:

```bash
compose down -v
```

> Chỉ dùng `compose down -v` khi muốn reset sạch database.

## Dữ liệu MySQL được lưu ở đâu?

- Named volume: **`apple_store_mysql_data`** (trong `compose.yaml` khai báo là `mysql_data`)
- `compose stop` — **không** mất dữ liệu
- `compose down` — **không** mất dữ liệu (nếu không có `-v`)
- `compose down -v` — **xóa** volume và mất toàn bộ dữ liệu MySQL

Podman lưu volume tương tự Docker; xem danh sách:

```bash
podman volume ls
# hoặc
docker volume ls
```

## Kiến trúc container

| Service | Mô tả |
|---------|--------|
| `app` | PHP 8.3 + Apache, document root `public/`, cổng **8080** |
| `mysql` | MySQL 8.4, named volume `mysql_data` |

Frontend (Vite) được build sẵn trong image khi `compose build`. Không cần `npm run dev` để test cơ bản.

## Podman trên Linux (SELinux / quyền ghi)

Nếu `storage/` hoặc upload ảnh báo lỗi permission với **rootless Podman**, dùng file override:

```bash
podman compose -f compose.yaml -f compose.podman.yaml up -d --build
```

File `compose.podman.yaml` bật `userns_mode: keep-id` và nhãn volume `:Z` cho SELinux. **Không** dùng override này với Docker Desktop trên Mac/Windows.

## Ghi chú tương thích

| Tình huống | Gợi ý |
|------------|--------|
| Đang dùng Podman | `podman compose` hoặc `.\scripts\compose.ps1` |
| Đang dùng Docker | `docker compose` hoặc `.\scripts\compose.ps1` |
| Cả hai đều cài | Script wrapper **ưu tiên Podman** |
| `podman-compose` (bản Python cũ) | Script bash vẫn hỗ trợ fallback |
| Build image riêng | `podman build -t apple-store-app .` hoặc `docker build -t apple-store-app .` |

---

# iStore — Apple Store Web App

Website bán iPhone, iPad, iPod và phụ kiện sạc (đồ án học tập), xây dựng bằng **Laravel 13**, **Blade**, **Tailwind CSS 4**, **jQuery** và **MySQL**.

---

## 1. Giới thiệu

### Mục đích

iStore mô phỏng cửa hàng Apple quy mô nhỏ: khách tìm kiếm/lọc sản phẩm, xem chi tiết biến thể (màu, dung lượng), thêm giỏ hàng, đặt hàng COD và xem lịch sử đơn. Quản trị viên có dashboard thống kê, quản lý catalog, đơn hàng và khách hàng.

### Công nghệ

| Thành phần | Phiên bản |
|------------|-----------|
| PHP | ^8.3 |
| Laravel | 13.x |
| MySQL | 8.x |
| Tailwind CSS | 4.x (Vite) |
| jQuery | 4.x |
| Node.js | 22.x khuyến nghị |

Không dùng React, Vue, Livewire, Inertia hay Alpine.

### Chức năng đã có

- Đăng ký, đăng nhập, hồ sơ, đổi mật khẩu
- Trang chủ: carousel sản phẩm nổi bật (ảnh demo), link danh mục nhanh
- Danh sách sản phẩm: tìm kiếm, lọc, sắp xếp, phân trang (SSR + AJAX filter)
- Chi tiết sản phẩm, chọn biến thể, thêm giỏ
- Giỏ hàng session (AJAX)
- Checkout COD (transaction, trừ tồn kho)
- Lịch sử và chi tiết đơn hàng (khách hàng)
- Admin catalog: danh mục, dòng sản phẩm, màu, dung lượng, sản phẩm, ảnh và biến thể
- Admin đơn hàng: lọc, đổi trạng thái, hủy đơn và hoàn tồn kho
- Admin khách hàng: tìm kiếm, khóa/mở tài khoản
- Admin dashboard: tổng sản phẩm, khách, đơn, doanh thu, đơn mới, cảnh báo tồn kho thấp

### Tài khoản demo (sau `php artisan db:seed`)

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | `admin@istore.test` | `password` |
| Khách hàng | `customer1@istore.test` … `customer5@istore.test` | `password` |

### Tài liệu thêm

| File | Nội dung |
|------|----------|
| [`docs/SPEC.md`](docs/SPEC.md) | Đặc tả chức năng |
| [`docs/TASKS.md`](docs/TASKS.md) | Kế hoạch theo phase |
| [`docs/CHECKLIST.md`](docs/CHECKLIST.md) | Tiến độ task |
| [`docs/PRODUCTION_CHECKLIST.md`](docs/PRODUCTION_CHECKLIST.md) | Rà soát trước khi deploy |
| [`docs/DEVELOPMENT_WINDOWS_LARAGON.md`](docs/DEVELOPMENT_WINDOWS_LARAGON.md) | Laragon chi tiết (Windows) |
| [`docs/DATABASE.md`](docs/DATABASE.md) | Schema & seeder |
| [`todo/NEEDS_HELP.md`](todo/NEEDS_HELP.md) | Hướng dẫn chuẩn bị ảnh sản phẩm |

### Kiểm tra nhanh sau khi cài

```bash
php artisan test
npm run build
```

Kỳ vọng: toàn bộ test pass, build Vite thành công.

**Bắt đầu nhanh theo hệ điều hành:** [macOS](#2-cài-đặt-trên-macos) · [Laragon (Windows)](#3-cài-đặt-trên-laragon-windows) · [XAMPP (Windows)](#4-cài-đặt-trên-xampp-windows)

---

## 2. Cài đặt trên macOS

Hướng dẫn phát triển trên Mac (Intel hoặc Apple Silicon). Khuyến nghị dùng [Homebrew](https://brew.sh/) để cài PHP, MySQL và Node.

### Yêu cầu

- macOS 13+ (Ventura trở lên khuyến nghị)
- PHP **8.3+**, Composer 2.x, MySQL 8.x, Node.js **22.x** và npm 10.x
- Extension PHP: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`

Cài qua Homebrew (nếu chưa có):

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

brew install php@8.3 composer mysql node
brew link php@8.3 --force --overwrite
brew services start mysql
```

Kiểm tra:

```bash
php --version
composer --version
mysql --version
node --version
```

### Bước 1 — Clone project

```bash
cd ~/Sites   # hoặc thư mục bạn hay đặt code
git clone <url-repo> apple-store-web-app
cd apple-store-web-app
```

### Bước 2 — Cài dependency

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

### Bước 3 — Cấu hình `.env`

Chỉnh file `.env` (không commit file này):

```dotenv
APP_NAME="iStore"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apple_store
DB_USERNAME=root
DB_PASSWORD=

# Tuỳ chọn — phí ship & cảnh báo tồn kho (VND)
SHIPPING_FEE=30000
SHIPPING_FREE_THRESHOLD=10000000
LOW_STOCK_THRESHOLD=5
```

MySQL Homebrew mặc định user `root`, mật khẩu trống. Nếu đã đặt mật khẩu, cập nhật `DB_PASSWORD`.

### Bước 4 — Database

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
```

### Bước 5 — Chạy

Mở **hai terminal** trong thư mục project:

```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite (khi sửa CSS/JS)
npm run dev
```

Mở trình duyệt:

| Trang | URL |
|-------|-----|
| Trang chủ | http://127.0.0.1:8000 |
| Sản phẩm | http://127.0.0.1:8000/products |
| Admin | http://127.0.0.1:8000/admin |

Đăng nhập admin: `admin@istore.test` / `password`.

### Tuỳ chọn — Laravel Herd

Nếu dùng [Laravel Herd](https://herd.laravel.com/), park thư mục project và truy cập hostname `.test` (ví dụ `http://apple-store-web-app.test`). Đặt `APP_URL` khớp hostname Herd. Vẫn cần `npm run dev` khi sửa frontend.

### Import database có sẵn (tuỳ chọn)

```bash
mysql -u root apple_store < todo/script.sql
```

### Lỗi thường gặp (macOS)

| Triệu chứng | Cách xử lý |
|-------------|------------|
| `command not found: php` | `brew link php@8.3 --force`; thêm PHP vào PATH theo gợi ý sau `brew install` |
| Lỗi kết nối MySQL | `brew services start mysql`; kiểm tra `DB_HOST`, `DB_PASSWORD` |
| `Vite manifest not found` | Chạy `npm run build` hoặc `npm run dev` |
| Port 8000 đã dùng | `php artisan serve --port=8001` và cập nhật `APP_URL` |
| Ảnh sản phẩm không hiện | Chạy `php artisan storage:link`; seed lại nếu cần |

---

## 3. Cài đặt trên Laragon (Windows)

Laragon là môi trường phát triển **khuyến nghị trên Windows** cho dự án này.

### Yêu cầu

- [Laragon](https://laragon.org/) (Apache hoặc Nginx + MySQL + PHP 8.3)
- Composer 2.x
- Node.js 22.x và npm 10.x
- Git (tùy chọn)

Kiểm tra:

```powershell
php --version
composer --version
mysql --version
node --version
```

### Bước 1 — Đặt project

Clone hoặc copy vào thư mục web của Laragon:

```
C:\laragon\www\apple-store-web-app
```

Laragon tự tạo hostname theo tên folder:

```
http://apple-store-web-app.test
```

Document root trỏ vào `public/` — **không** cần `php artisan serve`.

### Bước 2 — Cài dependency

Mở terminal tại thư mục project (nên dùng **Laragon Terminal** để PATH đúng):

```powershell
cd C:\laragon\www\apple-store-web-app

composer install
npm install

Copy-Item .env.example .env
php artisan key:generate
```

### Bước 3 — Cấu hình `.env`

Chỉnh file `.env` (không commit file này):

```dotenv
APP_NAME="iStore"
APP_URL=http://apple-store-web-app.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apple_store
DB_USERNAME=root
DB_PASSWORD=

# Tuỳ chọn — phí ship & cảnh báo tồn kho (VND)
SHIPPING_FEE=30000
SHIPPING_FREE_THRESHOLD=10000000
LOW_STOCK_THRESHOLD=5
```

### Bước 4 — Database

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS apple_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
```

### Bước 5 — Chạy

1. Laragon → **Start All** (Apache/Nginx + MySQL).
2. Nếu hostname `.test` chưa hoạt động: **Menu → Reload**.
3. Mở trình duyệt:

| Trang | URL |
|-------|-----|
| Trang chủ | http://apple-store-web-app.test |
| Sản phẩm | http://apple-store-web-app.test/products |
| Admin | http://apple-store-web-app.test/admin |

### Vite khi sửa CSS/JS

Terminal riêng, giữ chạy:

```powershell
npm run dev
```

Vẫn truy cập site qua Laragon (`http://apple-store-web-app.test`), không mở `localhost:5173` trực tiếp.

### Import database có sẵn (tuỳ chọn)

Có file dump tại [`todo/script.sql`](todo/script.sql):

```powershell
mysql -u root apple_store < todo\script.sql
```

---

## 4. Cài đặt trên XAMPP (Windows)

XAMPP dùng được nếu bạn đã quen Apache + MySQL của XAMPP. Dự án yêu cầu **PHP 8.3+** — kiểm tra phiên bản PHP trong XAMPP (`php --version`). Nếu XAMPP đi kèm PHP cũ hơn, cần nâng cấp PHP hoặc dùng Laragon.

### Yêu cầu

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.3+)
- Composer, Node.js (cài riêng, thêm vào PATH)
- Extension PHP: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`

### Bước 1 — Đặt project

Copy project vào:

```
C:\xampp\htdocs\apple-store-web-app
```

### Bước 2 — Cài dependency

```powershell
cd C:\xampp\htdocs\apple-store-web-app

composer install
npm install

Copy-Item .env.example .env
php artisan key:generate
```

### Bước 3 — Cấu hình `.env`

**Cách A — Truy cập qua thư mục `public` (đơn giản nhất)**

```dotenv
APP_URL=http://localhost/apple-store-web-app/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apple_store
DB_USERNAME=root
DB_PASSWORD=
```

URL truy cập: `http://localhost/apple-store-web-app/public`

**Cách B — Virtual host (khuyến nghị, URL gọn hơn)**

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

2. Mở `C:\Windows\System32\drivers\etc\hosts` (quyền Administrator), thêm:

```
127.0.0.1   apple-store.local
```

3. Trong `.env`:

```dotenv
APP_URL=http://apple-store.local
```

4. Khởi động lại Apache từ XAMPP Control Panel.

URL truy cập: `http://apple-store.local`

### Bước 4 — Bật `mod_rewrite`

Trong `C:\xampp\apache\conf\httpd.conf`:

- Bỏ comment dòng `LoadModule rewrite_module modules/mod_rewrite.so`
- Với thư mục `htdocs`, đặt `AllowOverride All` (Virtual host ở Cách B đã có sẵn)

Khởi động lại Apache.

### Bước 5 — Database

1. XAMPP Control Panel → **Start** Apache và MySQL.
2. Mở http://localhost/phpmyadmin
3. Tạo database `apple_store`, collation `utf8mb4_unicode_ci`.
4. Trong terminal project:

```powershell
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
```

MySQL XAMPP mặc định: user `root`, mật khẩu **trống**. Nếu bạn đã đặt mật khẩu root, cập nhật `DB_PASSWORD` trong `.env`.

### Bước 6 — Chạy và kiểm tra

| Trang | URL (Cách A) | URL (Cách B) |
|-------|----------------|--------------|
| Trang chủ | http://localhost/apple-store-web-app/public | http://apple-store.local |
| Admin | …/public/admin | http://apple-store.local/admin |

### Vite trên XAMPP

Giống Laragon — terminal riêng:

```powershell
npm run dev
```

Truy cập site vẫn qua URL XAMPP đã cấu hình ở trên.

### Lỗi thường gặp (XAMPP)

| Triệu chứng | Cách xử lý |
|-------------|------------|
| 404 trên mọi route trừ `/` | Bật `mod_rewrite`, `AllowOverride All`, document root phải là `public/` |
| `Vite manifest not found` | Chạy `npm run build` hoặc `npm run dev` |
| Lỗi kết nối MySQL | Kiểm tra MySQL đã Start; đúng `DB_HOST`, `DB_PASSWORD` |
| CSS/JS không load | Kiểm tra `APP_URL` khớp URL trình duyệt |
| PHP quá cũ | Cần PHP 8.3+; cân nhắc chuyển sang Laragon |

### Dự phòng: `artisan serve`

Nếu Apache gặp vấn đề, chạy tạm:

```powershell
php artisan serve
```

Đặt `APP_URL=http://127.0.0.1:8000` trong `.env` và mở http://127.0.0.1:8000 (chạy thêm `npm run dev` nếu sửa giao diện).

---

## Lưu ý chung

- File `.env` nằm trong `.gitignore` — không commit.
- Test tự động dùng SQLite in-memory; môi trường dev dùng MySQL.
- Ảnh sản phẩm: placeholder tại `public/images/placeholders/product-placeholder.svg` cho đến khi có ảnh demo (xem [`todo/NEEDS_HELP.md`](todo/NEEDS_HELP.md)).
- Đây là dự án học tập, không phải cửa hàng Apple chính thức.
