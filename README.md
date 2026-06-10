# iStore — Apple Store Web App

Website bán iPhone, iPad, iPod và phụ kiện sạc (đồ án học tập), xây dựng bằng **Laravel 13**, **Blade**, **Tailwind CSS 4**, **jQuery** và **MySQL**.

---

## 1. Giới thiệu

### Mục đích

iStore mô phỏng cửa hàng Apple quy mô nhỏ: khách tìm kiếm/lọc sản phẩm, xem chi tiết biến thể (màu, dung lượng), thêm giỏ hàng, đặt hàng COD và xem lịch sử đơn. Quản trị viên có dashboard placeholder và quản lý catalog.

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
- Danh sách sản phẩm: tìm kiếm, lọc, sắp xếp, phân trang (SSR + AJAX filter)
- Chi tiết sản phẩm, chọn biến thể, thêm giỏ
- Giỏ hàng session (AJAX)
- Checkout COD (transaction, trừ tồn kho)
- Lịch sử và chi tiết đơn hàng (khách hàng)
- Admin catalog: danh mục, dòng sản phẩm, màu, dung lượng, sản phẩm, ảnh và biến thể
- Admin dashboard (placeholder)

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
| [`docs/DEVELOPMENT_WINDOWS_LARAGON.md`](docs/DEVELOPMENT_WINDOWS_LARAGON.md) | Laragon chi tiết |
| [`todo/NEEDS_HELP.md`](todo/NEEDS_HELP.md) | Hướng dẫn chuẩn bị ảnh sản phẩm |

### Kiểm tra nhanh sau khi cài

```powershell
php artisan test
npm run build
```

Kỳ vọng: **135 tests passed**, build Vite thành công.

---

## 2. Cài đặt trên Laragon

Laragon là môi trường phát triển **khuyến nghị** cho dự án này.

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

## 3. Cài đặt trên XAMPP

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
