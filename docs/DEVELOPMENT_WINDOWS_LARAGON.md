# MÔI TRƯỜNG PHÁT TRIỂN WINDOWS VÀ LARAGON

## 1. Môi trường chính thức của dự án

Dự án được phát triển trên:

- Hệ điều hành: Windows.
- Local development environment: Laragon.
- Web server: Apache hoặc Nginx do Laragon quản lý.
- PHP: phiên bản tương thích với phiên bản Laravel được khóa trong `composer.json`.
- Database: MySQL do Laragon quản lý.
- Terminal khuyến nghị: PowerShell.
- IDE: Cursor.
- Node.js và npm: dùng để chạy Vite và build Tailwind CSS.
- Composer: dùng để quản lý dependency PHP.

Các hướng dẫn phát triển trong repository phải ưu tiên Windows và Laragon. Không được mặc định rằng người phát triển đang dùng Linux, macOS, Docker hoặc WSL.

## 2. Vị trí project

Khuyến nghị đặt project trong thư mục web root của Laragon.

Ví dụ:

    C:\laragon\www\apple-store

Nếu Laragon được cài ở vị trí khác, dùng đường dẫn tương ứng.

Không hard-code `C:\laragon` trong mã nguồn ứng dụng. Đường dẫn này chỉ dùng trong tài liệu môi trường phát triển.

## 3. Yêu cầu phần mềm

Trước khi bắt đầu, kiểm tra:

    php --version
    composer --version
    mysql --version
    node --version
    npm --version
    git --version

Nếu command không được nhận diện:

1. Mở Laragon.
2. Dùng terminal được mở từ Laragon.
3. Hoặc thêm PHP, Composer, MySQL, Node.js và Git vào biến môi trường `PATH`.
4. Đóng và mở lại Cursor sau khi thay đổi `PATH`.

Không trộn nhiều phiên bản PHP giữa Laragon, PowerShell hệ thống và Cursor terminal.

## 4. Khởi tạo project mới

Mở PowerShell hoặc Laragon Terminal:

    cd C:\laragon\www
    composer create-project laravel/laravel apple-store
    cd apple-store

Sau đó sao chép bộ tài liệu Cursor vào root project.

Cấu trúc mong đợi:

    C:\laragon\www\apple-store
    ├── AGENTS.md
    ├── docs
    ├── .cursor
    ├── app
    ├── database
    ├── public
    ├── resources
    └── routes

## 5. Cấu hình Laragon

### 5.1. Khởi động dịch vụ

Trong Laragon:

1. Start All.
2. Kiểm tra web server đã chạy.
3. Kiểm tra MySQL đã chạy.
4. Không chạy đồng thời một Apache hoặc MySQL khác đang chiếm cùng port.

Các port thường gặp:

- HTTP: 80.
- HTTPS: 443.
- MySQL: 3306.

Nếu port bị chiếm, kiểm tra service khác trước khi đổi cấu hình dự án.

### 5.2. Virtual host

Khi project nằm trong thư mục `www`, Laragon có thể tạo hostname cục bộ theo tên thư mục.

Ví dụ hostname:

    http://apple-store.test

Nếu hostname chưa hoạt động:

1. Reload Laragon.
2. Kiểm tra tính năng virtual host của Laragon.
3. Kiểm tra file hosts của Windows.
4. Chạy Laragon với quyền phù hợp nếu Windows chặn việc cập nhật hosts.

Không hard-code hostname local vào logic nghiệp vụ.

### 5.3. Document root

Document root phải trỏ vào thư mục:

    C:\laragon\www\apple-store\public

Không public trực tiếp root Laravel.

## 6. Tạo database MySQL

Có thể dùng HeidiSQL, phpMyAdmin hoặc MySQL CLI.

Tên database khuyến nghị:

    apple_store

Charset và collation khuyến nghị:

    utf8mb4
    utf8mb4_unicode_ci

Ví dụ bằng MySQL CLI:

    mysql -u root -p

Sau đó:

    CREATE DATABASE apple_store
      CHARACTER SET utf8mb4
      COLLATE utf8mb4_unicode_ci;

Trong Laragon local, tài khoản root có thể không có mật khẩu tùy cấu hình máy. Không đưa mật khẩu thật vào Git.

## 7. Cấu hình `.env`

Sao chép file mẫu:

    Copy-Item .env.example .env

Thiết lập tối thiểu:

    APP_NAME="Apple Store"
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://apple-store.test

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=apple_store
    DB_USERNAME=root
    DB_PASSWORD=

Sau đó:

    php artisan key:generate
    php artisan config:clear

Quy tắc:

- Không commit `.env`.
- Chỉ commit `.env.example`.
- Không đặt credential production vào môi trường local.
- Nếu MySQL Laragon dùng mật khẩu, cập nhật `DB_PASSWORD` trên máy cá nhân.
- Khi đổi `.env`, chạy lại `php artisan config:clear`.

## 8. Cài dependency

Trong PowerShell tại root project:

    composer install
    npm install

Không dùng đồng thời nhiều package manager JavaScript trong dự án. Dùng npm nếu repository đã có `package-lock.json`.

## 9. Chạy migration và seed

    php artisan migrate
    php artisan db:seed

Trong giai đoạn phát triển, khi cần tạo lại toàn bộ database demo:

    php artisan migrate:fresh --seed

Không chạy `migrate:fresh` trên database có dữ liệu cần giữ.

## 10. Chạy ứng dụng

### 10.1. Dùng Laragon virtual host

Khi Laragon quản lý web server, không bắt buộc chạy `php artisan serve`.

Mở:

    http://apple-store.test

### 10.2. Chạy Vite development server

Mở một terminal riêng:

    npm run dev

Giữ terminal này chạy khi phát triển giao diện.

### 10.3. Phương án dự phòng

Nếu virtual host chưa cấu hình được:

    php artisan serve

Sau đó truy cập URL được Artisan hiển thị.

Đây chỉ là phương án dự phòng; môi trường chuẩn của dự án vẫn là Laragon.

## 11. Các command PowerShell thường dùng

Cài dependency:

    composer install
    npm install

Khởi tạo ứng dụng:

    php artisan key:generate
    php artisan migrate
    php artisan db:seed

Chạy test:

    php artisan test

Build frontend:

    npm run build

Xóa cache local:

    php artisan optimize:clear

Kiểm tra route:

    php artisan route:list

Kiểm tra Git:

    git status
    git diff

PowerShell không dùng cú pháp shell Linux như:

    export VARIABLE=value
    rm -rf folder
    cp source destination

Thay thế bằng PowerShell:

    $env:VARIABLE = "value"
    Remove-Item folder -Recurse -Force
    Copy-Item source destination

Cursor Agent phải ưu tiên command tương thích PowerShell khi đưa hướng dẫn cho người dùng.

## 12. Upload và symbolic link

Nếu ứng dụng lưu ảnh bằng Laravel public disk:

    php artisan storage:link

Trên Windows, command có thể cần quyền tạo symbolic link.

Nếu thất bại:

1. Mở Windows Developer Mode.
2. Hoặc chạy terminal với quyền phù hợp.
3. Kiểm tra thư mục `public\storage`.
4. Không tự sao chép file upload thủ công như một giải pháp lâu dài.

Ảnh sản phẩm nên được lưu qua Laravel Storage API.

## 13. Quy tắc đường dẫn

Trong PHP và Laravel:

- Dùng helper như `storage_path()`, `public_path()`, `resource_path()` và `base_path()`.
- Không ghép đường dẫn Windows bằng chuỗi hard-code.
- Không lưu đường dẫn tuyệt đối `C:\...` vào database.
- Database chỉ lưu đường dẫn tương đối hoặc storage path logic.

Ví dụ hợp lệ:

    products/iphone-16/image-01.webp

Ví dụ không hợp lệ:

    C:\laragon\www\apple-store\public\uploads\image-01.webp

## 14. Cấu hình Cursor terminal

Cursor terminal phải dùng được cùng toolchain với Laragon.

Kiểm tra trong Cursor:

    where.exe php
    where.exe composer
    where.exe mysql
    php --ini
    php --version

Nếu `where.exe php` trả nhiều đường dẫn:

- Ưu tiên PHP do Laragon quản lý.
- Không để Cursor dùng một PHP khác với terminal Laragon.
- Khởi động lại Cursor sau khi đổi `PATH`.

## 15. PHP extensions

Laravel và các package có thể cần một số extension như:

- OpenSSL.
- PDO MySQL.
- Mbstring.
- Tokenizer.
- XML.
- Ctype.
- Fileinfo.
- BCMath, tùy dependency.

Kiểm tra:

    php -m

Bật extension bằng Laragon hoặc file `php.ini` đang được command line PHP sử dụng.

Luôn kiểm tra:

    php --ini

trước khi sửa `php.ini`, để tránh sửa nhầm phiên bản PHP.

## 16. MySQL và timezone

Khuyến nghị ứng dụng dùng timezone:

    Asia/Ho_Chi_Minh

Cấu hình trong Laravel phải nhất quán.

Database lưu timestamp theo chính sách mà nhóm đã chọn. Không tự cộng hoặc trừ 7 giờ trong controller hoặc Blade.

## 17. Kiểm thử trên Windows

Trước khi hoàn thành feature:

    php artisan test
    npm run build
    git diff
    git status

Nếu test liên quan file upload:

- Dùng fake storage.
- Không phụ thuộc vào đường dẫn tuyệt đối Windows.
- Không ghi test file vào thư mục upload thật.

Nếu test database:

- Dùng database test riêng.
- Không trỏ test vào database development có dữ liệu.

## 18. Xử lý lỗi thường gặp

### PHP command không đúng phiên bản

Kiểm tra:

    where.exe php
    php --version
    php --ini

Sau đó đồng bộ `PATH` với PHP của Laragon.

### MySQL connection refused

Kiểm tra:

- MySQL trong Laragon đã Start.
- `DB_HOST=127.0.0.1`.
- Port đúng.
- Database đã tồn tại.
- Username và password đúng.
- Không có MySQL khác chiếm port.

### `Vite manifest not found`

Chạy:

    npm install
    npm run dev

Hoặc:

    npm run build

### Thay đổi `.env` chưa có hiệu lực

Chạy:

    php artisan optimize:clear

### Hostname `.test` không mở được

- Reload Laragon.
- Kiểm tra virtual host.
- Kiểm tra Windows hosts file.
- Kiểm tra web server.
- Thử `php artisan serve` để xác định lỗi thuộc Laragon hay ứng dụng.

### Symbolic link thất bại

- Bật Developer Mode.
- Dùng quyền phù hợp.
- Xóa link hỏng trước khi chạy lại.
- Không commit symbolic link được tạo riêng cho máy nếu repository không yêu cầu.

## 19. Quy tắc dành cho Cursor Agent

Khi đưa command hoặc hướng dẫn:

- Giả định người dùng dùng Windows và PowerShell.
- Giả định local web server và MySQL được Laragon quản lý.
- Không yêu cầu Docker.
- Không yêu cầu WSL.
- Không dùng lệnh Bash nếu chưa cung cấp bản PowerShell tương đương.
- Không sửa file cấu hình Laragon toàn cục nếu task chỉ cần sửa ứng dụng.
- Không hard-code đường dẫn máy cá nhân.
- Không yêu cầu người dùng chạy `php artisan serve` nếu Laragon virtual host đang hoạt động.
- Khi debug PHP version, luôn kiểm tra `where.exe php`, `php --version` và `php --ini`.
