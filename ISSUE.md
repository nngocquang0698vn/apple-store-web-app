# ISSUE — Docker / Podman Compose

Tóm tắt các vấn đề gặp phải khi đóng gói và chạy dự án bằng `podman compose` / `docker compose`.

---

## 1. Build image thất bại (`pdo_sqlite`)

**Triệu chứng**

```text
configure: error: Package requirements (sqlite3 >= 3.7.7) were not met
Package 'sqlite3', required by 'virtual:world', not found
```

**Nguyên nhân:** `Dockerfile` cài extension `pdo_sqlite` (phục vụ `php artisan test` trong container) nhưng thiếu gói hệ thống `libsqlite3-dev`.

**Cách xử lý:** Thêm `libsqlite3-dev` vào bước `apt-get install` trong `Dockerfile`.

**Trạng thái:** Đã sửa.

---

## 2. `podman compose` trên Windows dùng provider ngoài

**Triệu chứng**

```text
>>>> Executing external compose provider "...\docker-compose.exe". Please see podman-compose(1)...
```

**Nguyên nhân:** Podman Desktop trên Windows ủy quyền cho `docker-compose.exe` thay vì plugin compose native. Đây là cảnh báo thông tin, không phải lỗi dừng chạy.

**Cách xử lý:** Có thể bỏ qua nếu lệnh vẫn chạy thành công. Cần **bật Podman machine** trước khi `podman compose up`.

**Trạng thái:** Đã ghi chú trong `README.md`.

---

## 3. Cấu hình Compose / README quá rối

**Triệu chứng**

- Nhiều file trùng chức năng: `compose.yaml`, `docker-compose.yml`, `compose.podman.yaml`, `scripts/compose.sh`, `scripts/compose.ps1`.
- Tester khó biết chạy lệnh nào.

**Cách xử lý**

- Giữ một file chính: `compose.yaml`.
- Xóa script wrapper và file alias/override không cần thiết.
- Rút gọn phần Docker/Podman ở đầu `README.md`; dùng trực tiếp `podman compose` hoặc `docker compose`.

**Trạng thái:** Đã sửa.

---

## 4. File `.env` không đúng môi trường container

**Triệu chứng**

- Container không kết nối được MySQL (`DB_HOST=127.0.0.1` từ Laragon).
- Session/cache dùng `database` trong khi `.env.docker.example` dùng `file` / `sync`.

**Nguyên nhân:** Copy nhầm `.env` Laragon thay vì `.env.docker.example`, hoặc mount `.env` host vào container khi file chưa tồn tại (Podman/Docker có thể tạo thư mục rỗng thay vì file).

**Cách xử lý**

1. Luôn tạo `.env` từ `.env.docker.example` **trước** `compose up`.
2. `compose.yaml` chỉ override các biến DB trỏ tới service `mysql`:

```yaml
DB_HOST: mysql
DB_PORT: "3306"
DB_DATABASE: apple_store
DB_USERNAME: apple_store
DB_PASSWORD: secret
```

**Trạng thái:** Đã sửa trong `compose.yaml` và `README.md`.

---

## 5. `php artisan test` trong container trả 419 (CSRF)

**Triệu chứng**

```text
Expected response status code [302] but received 419.
```

Nhiều feature test POST (login, cart, admin upload…) fail trong container; chạy `php artisan test` trên host (Laragon) vẫn pass.

**Nguyên nhân:** Biến môi trường của container (compose `DB_HOST=mysql`, `.env` với `SESSION_DRIVER=database`, …) **ghi đè** giá trị trong `phpunit.xml`. Hệ quả:

- POST test nhận **419** (session/CSRF không dùng `array` như test mong đợi).
- Feature test chạy trên **MySQL thật** thay vì SQLite `:memory:` → thiếu bảng, deadlock khi seed song song.

**Cách xử lý:**

1. `compose.yaml` chỉ override biến DB cho app runtime; không set `APP_ENV`, `APP_DEBUG`, `APP_URL`.
2. `phpunit.xml` dùng `force="true"` cho `APP_ENV`, `SESSION_DRIVER`, `DB_*` để test luôn dùng SQLite in-memory trong container.

```bash
podman compose exec app php artisan test
```

**Trạng thái:** Đã sửa `compose.yaml` và `phpunit.xml`. **Chưa verify pass 100% trong container** (xem mục 8).

---

## 6. Mount `bootstrap/cache` từ host

**Triệu chứng (tiềm ẩn):** Config/route cache từ Laragon trên Windows được mount vào container, gây hành vi lạ hoặc test không ổn định.

**Cách xử lý:** Bỏ volume `./bootstrap/cache` khỏi `compose.yaml`; để container tự quản lý cache.

**Trạng thái:** Đã bỏ mount.

---

## 7. Image không mount mã nguồn — phải rebuild sau khi đổi code

**Triệu chứng:** Sửa `phpunit.xml` (hoặc PHP/Blade/JS…) trên host nhưng `podman compose exec app php artisan test` vẫn hành vi cũ.

**Nguyên nhân:** `compose.yaml` chỉ mount:

- `./.env` → `/var/www/html/.env`
- `./storage` → `/var/www/html/storage`

Phần còn lại (gồm `phpunit.xml`, `app/`, `tests/`, …) nằm trong **image đã build**, không đồng bộ realtime với thư mục Laragon.

**Cách xử lý:** Sau khi đổi code cần test/chạy trong container:

```bash
podman compose up -d --build
```

**Trạng thái:** Đã ghi chú. Cân nhắc mai: mount thêm source cho dev, hoặc giữ rebuild + chạy test trên host Laragon.

---

## 8. Kết quả verify `migrate --seed` + `php artisan test` (11/06/2026)

### `migrate --seed` — OK

```bash
podman compose exec app php artisan migrate --seed --force
```

- Migration và `CatalogSeeder` chạy thành công trên MySQL trong container.
- App truy cập được tại http://localhost:8080 sau khi container up.

### `php artisan test` — chưa pass hết

**Lần 1** (trước khi sửa `phpunit.xml`):

```text
Tests: 97 failed, 118 passed
```

Lỗi chính: **419** (CSRF) trên POST; **QueryException** thiếu bảng (`product_series`, `colors`); **deadlock** MySQL — do test chạy trên DB thật thay vì SQLite `:memory:`.

**Lần 2** (đã thêm `force="true"` vào `phpunit.xml` trên host, **chưa rebuild image**):

```text
Tests: 76 failed, 139 passed
```

Cải thiện (CatalogSeederTest, ProductDiscoveryTest đã pass) nhưng vẫn **419** — container vẫn dùng `phpunit.xml` cũ trong image.

**Lần 3** (đã `podman compose up -d --build`):

- Image rebuild xong, container recreate.
- Chạy full `php artisan test` **bị interrupt** (~2 phút) — **chưa có kết quả cuối**.

**Trên host Laragon:** `php artisan test` vẫn pass (ví dụ `ProfileTest::test_user_can_update_profile`).

### Việc cần làm tiếp (mai)

1. Chạy lại full test trong container sau rebuild:

   ```bash
   podman compose exec app php artisan test
   ```

2. Nếu vẫn fail: dump config trong môi trường test (`SESSION_DRIVER`, `DB_CONNECTION`, `APP_KEY`) và so với host.
3. Quyết định workflow:
   - **Tester:** chỉ cần `compose up` + migrate/seed; test chạy trên Laragon.
   - **CI/container:** giữ `phpunit.xml` + `force="true"` và rebuild image mỗi lần đổi test config.

---

## 9. Thứ tự khởi động lần đầu

**Lưu ý cho tester**

```bash
cp .env.docker.example .env          # Windows: Copy-Item .env.docker.example .env
podman compose up -d --build
podman compose exec app php artisan key:generate   # nếu APP_KEY trống
podman compose exec app php artisan migrate --seed
podman compose exec app php artisan storage:link
```

Truy cập: http://localhost:8080  
Admin demo: `admin@istore.test` / `password`

**Dữ liệu MySQL:** volume `apple_store_mysql_data` — `compose stop` / `compose down` không xóa data; chỉ `compose down -v` mới reset DB.

---

## Checklist xác nhận nhanh

```bash
podman compose ps
podman compose exec app php artisan about
curl -I http://localhost:8080
podman compose exec app php artisan migrate --seed --force   # lần đầu hoặc sau khi đổi migration
podman compose exec app php artisan test                     # chưa verify pass hết — xem mục 8
```

Sau khi đổi `Dockerfile`, `phpunit.xml`, hoặc code PHP:

```bash
podman compose up -d --build
```
