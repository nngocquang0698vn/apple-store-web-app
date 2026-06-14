# iStore — Apple Store Web App

Website bán iPhone, iPad và phụ kiện (đồ án học tập): **Laravel 13**, **Blade SSR**, **Tailwind CSS 4**, **jQuery**, **MySQL**.

> **Sinh viên phát triển:** xem [README-DEV.md](README-DEV.md) (Laragon, test, build).

---

## Hướng dẫn cài đặt (dành cho thầy chấm bài)

Sinh viên nộp file **`apple-store-web-app-release.zip`**. Giải nén toàn bộ nội dung vào thư mục `www/` của XAMPP-Lite (file `artisan` nằm ngay trong `www/`).

Gói zip đã chuẩn bị sẵn — **không cần** Composer, Node.js, npm, sửa file `hosts` hay `php artisan migrate`.

| Đã có trong gói | Ghi chú |
| --- | --- |
| `vendor/` | Thư viện PHP |
| `public/build/` | CSS/JS đã build |
| `database/dumps/apple_store-demo.sql` | Dữ liệu demo (có `CREATE DATABASE`) |
| `public/storage/` | Ảnh sản phẩm (copy sẵn) |
| `.env` | `APP_KEY` + `APP_URL=http://127.0.0.1:8080` |
| `xampp-lite-conf/` | Cấu hình Apache mẫu (4 file `.example`) |

**Yêu cầu:** Windows 64-bit, [XAMPP-Lite 8.3.30](https://sourceforge.net/projects/xampplite/files/8.3/8.3.30/x64/php-man-en/) (PHP **8.3**).

> **Không dùng** [XAMPP Apache Friends](https://www.apachefriends.org/) — PHP quá cũ cho Laravel 13.

### Bước 1 — Tải XAMPP-Lite

[8.3.30 / x64](https://sourceforge.net/projects/xampplite/files/8.3/8.3.30/x64/php-man-en/) — khuyến nghị [portable.7z](https://sourceforge.net/projects/xampplite/files/8.3/8.3.30/x64/php-man-en/XAMPP-Lite-8.3.30.1-x64-php-man-en-portable.7z/download).

Kiểm tra: `php -v` → **8.3.x**.

### Bước 2 — Giải nén `apple-store-web-app-release.zip` vào `www/`

Giải nén **`apple-store-web-app-release.zip`** — copy **toàn bộ** nội dung (hoặc thư mục sau giải nén) vào `www/` — file `artisan` nằm **ngay trong** `www/`, không tạo subfolder.

```
C:\xampp_lite_8_3\www\artisan
C:\xampp_lite_8_3\www\.env
C:\xampp_lite_8_3\www\public\index.php
```

### Bước 3 — Cấu hình Apache (port 8080)

App Laravel chạy trên **http://127.0.0.1:8080** — **không cần sửa file `hosts`**.

**Vì sao dùng port 8080?** XAMPP-Lite trên `http://localhost` (cổng 80) đã chiếm đường dẫn `/admin` cho panel quản trị XAMPP. App Laravel cũng có `/admin`. Tách app sang cổng **8080** tránh xung đột; phpMyAdmin vẫn mở bình thường tại `http://localhost/phpmyadmin`.

Giả sử XAMPP-Lite nằm tại `C:\xampp_lite_8_3`. Thư mục cấu hình Apache:

```
C:\xampp_lite_8_3\apps\apache\conf\
C:\xampp_lite_8_3\apps\apache\conf\extra\
```

**Trước khi sửa:** Stop Apache trong XAMPP-Lite Control Panel (hoặc đóng cửa sổ Apache), sao lưu 4 file gốc nếu cần khôi phục.

---

#### Cách A — Ghi đè bằng file mẫu (khuyến nghị, nhanh nhất)

Trong gói nộp có sẵn 4 file đã chỉnh đúng. Copy **ghi đè** như sau:

| File trong gói nộp (`xampp-lite-conf/`) | Copy thành file trên XAMPP-Lite |
| --- | --- |
| `httpd.conf.example` | `apps\apache\conf\httpd.conf` |
| `httpd-vhosts.conf.example` | `apps\apache\conf\extra\httpd-vhosts.conf` |
| `httpd-xampp-lite-aliases.conf.example` | `apps\apache\conf\extra\httpd-xampp-lite-aliases.conf` *(tạo mới)* |
| `httpd-xampp-lite.conf.example` | `apps\apache\conf\extra\httpd-xampp-lite.conf` |

PowerShell (chạy **sau** khi đã copy project vào `www/`):

```powershell
$X = "C:\xampp_lite_8_3"
$W = "C:\xampp_lite_8_3\www"
$C = "$W\xampp-lite-conf"

Copy-Item "$C\httpd.conf.example" "$X\apps\apache\conf\httpd.conf" -Force
Copy-Item "$C\httpd-vhosts.conf.example" "$X\apps\apache\conf\extra\httpd-vhosts.conf" -Force
Copy-Item "$C\httpd-xampp-lite-aliases.conf.example" "$X\apps\apache\conf\extra\httpd-xampp-lite-aliases.conf" -Force
Copy-Item "$C\httpd-xampp-lite.conf.example" "$X\apps\apache\conf\extra\httpd-xampp-lite.conf" -Force
```

Sau đó nhảy tới **Bước 3.5 — Kiểm tra và khởi động lại Apache**.

---

#### Cách B — Sửa thủ công từng file (step by step)

Dùng Notepad hoặc trình soạn thảo bất kỳ. Mở file trên XAMPP-Lite (không sửa file `.example` trong gói nộp nếu muốn giữ bản mẫu để đối chiếu).

##### Bước 3.1 — `httpd.conf`: Apache lắng nghe cổng 8080

Mở `apps\apache\conf\httpd.conf`, tìm dòng:

```apache
Listen 127.0.0.1:80
```

Thêm **ngay bên dưới**:

```apache
Listen 127.0.0.1:8080
```

Kiểm tra dòng sau **không bị comment** (bật virtual host):

```apache
Include conf/extra/httpd-vhosts.conf
```

##### Bước 3.2 — Tạo `httpd-xampp-lite-aliases.conf` (file mới)

Tạo file `apps\apache\conf\extra\httpd-xampp-lite-aliases.conf`.

Copy **toàn bộ** nội dung từ `xampp-lite-conf/httpd-xampp-lite-aliases.conf.example` trong gói nộp (hoặc copy khối `Alias /admin`, `/phpmyadmin`, … từ file `httpd-xampp-lite.conf` gốc của XAMPP).

File này chứa alias panel XAMPP và phpMyAdmin — sẽ chỉ được load trên cổng **80**, không ảnh hưởng app trên **8080**.

##### Bước 3.3 — `httpd-xampp-lite.conf`: bỏ alias khỏi cấu hình chung

Mở `apps\apache\conf\extra\httpd-xampp-lite.conf`.

**Xóa** (hoặc comment) toàn bộ khối `<IfModule alias_module>` chứa các dòng `Alias /admin`, `Alias /phpmyadmin`, … — phần đó đã chuyển sang file ở bước 3.2.

Cuối file có thể để ghi chú:

```apache
# Aliases — moved to conf/extra/httpd-xampp-lite-aliases.conf
# (included only in localhost VirtualHost; see httpd-vhosts.conf)
```

##### Bước 3.4 — `httpd-vhosts.conf`: cổng 80 giữ XAMPP, cổng 8080 chạy Laravel

Mở `apps\apache\conf\extra\httpd-vhosts.conf`.

**4a.** VirtualHost mặc định cổng 80 — thêm dòng `Include` alias XAMPP (nếu chưa có):

```apache
<VirtualHost _default_:80>
  DocumentRoot "${XAMPP_LITE_ROOT}/www"
  ServerName 127.0.0.1:80
  ErrorLog "${XAMPP_LITE_ROOT}/tmp/apache_logs/apache_error.log"
  CustomLog "${XAMPP_LITE_ROOT}/tmp/apache_logs/apache_access.log" common
  Include conf/extra/httpd-xampp-lite-aliases.conf
</VirtualHost>
```

**4b.** Thêm **VirtualHost mới** cho app (đặt **cuối file**):

```apache
# iStore Laravel app (port 8080)
<VirtualHost *:8080>
    ServerName 127.0.0.1
    DocumentRoot "${XAMPP_LITE_ROOT}/www/public"
    <Directory "${XAMPP_LITE_ROOT}/www/public">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "${XAMPP_LITE_ROOT}/tmp/apache_logs/istore-error.log"
    CustomLog "${XAMPP_LITE_ROOT}/tmp/apache_logs/istore-access.log" common
</VirtualHost>
```

> `DocumentRoot` phải trỏ **`www/public`** (thư mục chứa `index.php` của Laravel), không phải `www/`.

##### Bước 3.5 — Kiểm tra và khởi động lại Apache

1. Start **Apache** trong XAMPP-Lite.
2. Mở trình duyệt:
   - http://127.0.0.1:8080 → trang chủ iStore
   - http://localhost/phpmyadmin → phpMyAdmin (cổng 80)
3. Nếu Apache không start: xem `tmp\apache_logs\apache_error.log` — thường do thiếu `Listen 8080` hoặc lỗi cú pháp trong `httpd-vhosts.conf`.

File `.env` trong gói nộp đã có `APP_URL=http://127.0.0.1:8080` — **không đổi** trừ khi bạn đổi port khác.

---

#### Tóm tắt: khác gì so với XAMPP-Lite gốc?

| File | Thay đổi |
| --- | --- |
| **`httpd.conf`** | Thêm `Listen 127.0.0.1:8080` |
| **`httpd-vhosts.conf`** | VirtualHost `:80` + `Include` aliases; **thêm** VirtualHost `:8080` → `www/public/` |
| **`httpd-xampp-lite.conf`** | **Xóa** block `Alias /admin`, `/phpmyadmin`, … |
| **`httpd-xampp-lite-aliases.conf`** | **File mới** — alias XAMPP chỉ cho cổng 80 |

### Bước 4 — Database

1. Start **Apache** + **MySQL**.
2. Mở `http://localhost/phpmyadmin` → **Import** → `database/dumps/apple_store-demo.sql`.

Sửa `DB_PASSWORD=` trong `.env` nếu MySQL root có mật khẩu.

### Bước 5 — Mở trang web

> **Quan trọng — tránh lỗi CORS / CSS-JS không load:** Luôn mở app bằng **`http://127.0.0.1:8080`**, **không** dùng `http://localhost:8080`.
>
> Gói nộp có `APP_URL=http://127.0.0.1:8080`. Trình duyệt coi `localhost` và `127.0.0.1` là **hai origin khác nhau** — nếu gõ `localhost:8080`, CSS/JS/font có thể bị chặn CORS vì Laravel sinh link asset theo `127.0.0.1`.
>
> **Khi demo / chấm bài:** bookmark và chỉ dùng `http://127.0.0.1:8080`. phpMyAdmin vẫn mở `http://localhost/phpmyadmin` (cổng 80).

| Trang | URL |
| --- | --- |
| Trang chủ | http://127.0.0.1:8080 |
| Sản phẩm | http://127.0.0.1:8080/products |
| Quản trị (app) | http://127.0.0.1:8080/admin |
| phpMyAdmin | http://localhost/phpmyadmin |

**Tài khoản demo:** Admin `admin@istore.test` / `password` · Khách `customer1@istore.test` / `password`

Kịch bản trình bày: `demo/demo.md`.

### Lỗi thường gặp

| Triệu chứng | Cách xử lý |
| --- | --- |
| `require PHP >= 8.3.0` | Dùng XAMPP-Lite 8.3.30 |
| Không mở được `:8080` | Đã thêm `Listen 127.0.0.1:8080` và copy đủ 4 file mẫu; restart Apache |
| 404 mọi route | VirtualHost `:8080` phải trỏ `www/public`; bật `mod_rewrite` |
| `/admin` mở panel XAMPP | App dùng **http://127.0.0.1:8080/admin**, không dùng `localhost` (cổng 80) |
| CORS / CSS-JS không load trên `:8080` | Đang mở **`localhost:8080`** — chuyển sang **`http://127.0.0.1:8080`** (khớp `APP_URL` trong `.env`) |
| Upload ảnh admin **403 Forbidden** | Thường do mở **`localhost:8080`** nhưng AJAX gọi **`127.0.0.1`** (mất session). Dùng **`http://127.0.0.1:8080`** xuyên suốt; hard refresh trang admin |
| Ảnh / CSS lỗi | `APP_URL=http://127.0.0.1:8080` trong `.env`; có `public/storage/` |
| Ảnh **upload mới** không hiện (404 `/storage/...`) | XAMPP không dùng `storage:link`. App tự copy vào `public/storage/` sau upload; ảnh cũ: chạy `robocopy storage\app\public public\storage /E` trong `www/` rồi copy code mới |
| Lỗi DB | MySQL đã Start; kiểm tra `DB_*` |

---

## Cách cài khác

### Docker / Podman

Upload ảnh admin hoạt động trên **XAMPP-Lite** (mirror `public/storage/`), **Laragon** (`storage:link`) và **Podman/Docker** (symlink trong container + lưu path ổn định).

**Lần đầu** (từ thư mục gốc project):

```powershell
Copy-Item .env.docker.example .env.docker
podman compose up -d --build
podman compose exec app php artisan migrate --seed --force
```

**Dựng lại sạch** (xóa volume, build lại, seed lại) — **Podman**:

```powershell
podman compose down -v
podman compose up -d --build
podman compose exec app php artisan migrate --seed --force
```

**Dựng lại sạch** — **Docker** (cùng thứ tự, đổi `podman` → `docker`):

```powershell
docker compose down -v
docker compose up -d --build
docker compose exec app php artisan migrate --seed --force
```

| Dịch vụ | URL |
| --- | --- |
| App | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |

> Cùng cổng **8080** với XAMPP-Lite — chỉ chạy **một** stack tại một thời điểm. Trên Podman/Docker dùng `localhost:8080` (khớp `APP_URL` trong `.env.docker`).

---

## Giới thiệu dự án

iStore mô phỏng cửa hàng Apple: catalog iPhone/iPad/phụ kiện, giỏ hàng AJAX, checkout COD, khu admin.

| Thành phần | Phiên bản |
| --- | --- |
| PHP | ^8.3 |
| Laravel | 13.x |
| MySQL | 8.x |
| Tailwind CSS | 4.x |
| jQuery | 4.x |

---

## Sinh viên: chuẩn bị gói nộp

```powershell
composer install
.\scripts\prepare-xampp.ps1
.\scripts\copy-release.ps1
```

Nén thủ công thư mục `apple-store-web-app-release` thành **`apple-store-web-app-release.zip`** (chuột phải → **Nén vào...** / 7-Zip), rồi nộp file zip.

---

## Lưu ý

- Dự án học tập, không phải cửa hàng Apple chính thức.
- Phát triển hàng ngày: [README-DEV.md](README-DEV.md).
