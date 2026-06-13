# Kịch bản demo iStore (~20 phút)

> **Mục tiêu:** Thể hiện đủ luồng e-commerce (khách + admin), kỹ thuật Laravel (SSR, AJAX, transaction, phân quyền) và phần **thêm sản phẩm mới** bằng bộ resource có sẵn trong `demo/products/`.

---

## 0. Chuẩn bị trước khi vào phòng (~10 phút)

### Môi trường

- **Dev (Laragon):** Start All → `http://apple-store-web-app.test` (xem [README-DEV.md](../README-DEV.md))
- **Nộp / chấm (XAMPP-Lite):** `http://127.0.0.1:8080` (xem [README.md](../README.md))
- Đã chạy: `php artisan migrate:fresh --seed`, `php artisan storage:link`, `npm run build`
- Mở sẵn 2 trình duyệt (hoặc 2 profile): **Khách** và **Admin**

### Tài khoản

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin | `admin@istore.test` | `password` |
| Khách demo | `customer1@istore.test` | `password` |

### Tab nên mở sẵn

1. Trang chủ `/`
2. Danh sách sản phẩm `/products`
3. Admin dashboard `/admin`
4. Thư mục `demo/products/iphone-17-pro/` (copy nội dung)

### Sản phẩm demo đề xuất thêm live

Chọn **một** trong ba (khuyến nghị **iPhone 17 Pro** — nhiều biến thể, ảnh đẹp):

| Thư mục | Tên SP | Giá tham khảo |
| --- | --- | --- |
| `demo/products/iphone-17-pro/` | iPhone 17 Pro | ~33.790.000đ |
| `demo/products/iphone-17/` | iPhone 17 | ~23.990.000đ |
| `demo/products/iphone-air/` | iPhone Air | ~22.890.000đ |

---

## 1. Phân bổ thời gian

| # | Phần | Phút | Ăn điểm |
| --- | --- | ---: | --- |
| 1 | Mở đầu + kiến trúc ngắn | 2 | Tổng quan đề tài |
| 2 | Luồng khách: tìm → lọc → chi tiết → giỏ | 6 | SSR + AJAX, biến thể |
| 3 | Đăng nhập + checkout COD | 4 | Auth, transaction, trừ kho |
| 4 | **Live demo: thêm sản phẩm mới** | 5 | Admin CRUD, upload ảnh |
| 5 | Admin: đơn hàng + dashboard | 2 | Quản trị, báo cáo |
| 6 | Kết + Q&A | 1 | Test, bảo mật |

**Tổng: ~20 phút**

---

## 2. Kịch bản chi tiết

### Phần 1 — Mở đầu (2 phút)

**Nói:**

> Xin chào thầy/cô và các bạn. Nhóm em xây dựng **iStore** — website bán iPhone, iPad và phụ kiện Apple phục vụ đồ án môn Internet và Công nghệ Web.
>
> Stack: **Laravel 13**, **Blade SSR**, **MySQL**, **Tailwind CSS 4**, **jQuery** cho tương tác động. Không dùng SPA/React.
>
> Hệ thống có hai vai trò: **khách hàng** (mua hàng COD) và **quản trị** (catalog, đơn hàng, khách hàng).

**Trình chiếu nhanh:** trang chủ → nhấn **Xem sản phẩm**.

---

### Phần 2 — Trải nghiệm khách hàng (6 phút)

#### 2.1 Trang chủ & danh mục (~1 phút)

- Carousel sản phẩm nổi bật, chip danh mục iPhone / iPad / Phụ kiện.
- Nếu đăng nhập admin: nút **Vào khu vực quản trị** cạnh CTA (phân quyền).

**Nói:** *Trang chủ SSR; dữ liệu lấy từ MySQL qua Eloquent.*

#### 2.2 Lọc & tìm kiếm AJAX (~2 phút)

Vào `/products`:

1. Gõ tìm **iPhone 16** → kết quả cập nhật không reload trang.
2. Chọn danh mục **iPhone**, dòng **iPhone 16 Series**.
3. Lọc màu / dung lượng (nếu có).
4. Đổi **Sắp xếp** → **Chuẩn** / **Giá thấp → cao**.

**Nói:** *jQuery gọi API nội bộ, server trả HTML partial — vẫn SEO-friendly vì lần đầu render từ server.*

#### 2.3 Chi tiết sản phẩm (~2 phút)

Mở **iPhone 16 Pro** (đã có sẵn trong seed):

1. **Gallery carousel** — nhiều ảnh, thumb tự cuộn.
2. Đổi **màu** → ảnh chính đổi theo biến thể.
3. Đổi **dung lượng** → giá và tồn kho cập nhật realtime.
4. Cuộn **mô tả chi tiết** (rich text).

**Nói:** *Mỗi SKU là một biến thể: màu + dung lượng + giá + tồn kho riêng.*

#### 2.4 Giỏ hàng AJAX (~1 phút)

- **Thêm vào giỏ** → toast thông báo, badge giỏ tăng.
- Vào `/cart` — sửa số lượng, xóa dòng.

**Nói:** *Giỏ lưu session; thao tác AJAX có CSRF.*

---

### Phần 3 — Đăng nhập & đặt hàng COD (4 phút)

1. **Đăng nhập** `customer1@istore.test` / `password`.
2. Thêm sản phẩm → **Thanh toán**.
3. Điền thông tin nhận hàng (có thể dùng sẵn hồ sơ).
4. **Đặt hàng COD** → trang xác nhận mã đơn.
5. Vào **Đơn hàng của tôi** → xem trạng thái **Chờ xác nhận**.

**Nói (quan trọng — ăn điểm kỹ thuật):**

> Checkout chạy trong **database transaction**: tạo đơn, chi tiết đơn, **trừ tồn kho** biến thể. Nếu hết hàng hoặc lỗi → rollback, không tạo đơn ảo.
>
> Phí ship cấu hình qua `.env` (`SHIPPING_FEE`, ngưỡng miễn phí).

---

### Phần 4 — Live demo: Thêm sản phẩm mới (5 phút) ⭐

> Dùng resource trong `demo/products/iphone-17-pro/` (hoặc iphone-17 / iphone-air).

#### Bước A — Chuẩn bị danh mục (30 giây, có thể làm trước)

Admin → **Dòng sản phẩm** → **Thêm** (nếu chưa có):

- Tên: `iPhone 17 Series`
- Danh mục: iPhone
- Năm: `2025`

Admin → **Màu sắc** → thêm (nếu thiếu): `Cam vũ trụ`, `Xanh đậm`, `Bạc` (xem `product.json`).

#### Bước B — Tạo sản phẩm (2 phút)

Admin → **Sản phẩm** → **Thêm sản phẩm**:

| Trường | Nguồn |
| --- | --- |
| Tên | `product.json` → `name` |
| Slug | `iphone-17-pro` |
| Danh mục / Dòng | iPhone / iPhone 17 Series |
| Mô tả ngắn | `short_description.txt` |
| Mô tả chi tiết | Copy `description.html` vào editor |
| Thông số | `specifications.txt` |
| Năm ra mắt | `2025` |
| Nổi bật + Đang hoạt động | Bật |

**Lưu** → vào trang chi tiết sản phẩm admin.

#### Bước C — Upload ảnh carousel (1,5 phút)

Kéo thả từ `demo/products/iphone-17-pro/images/`:

1. `cosmic-orange.webp` (ảnh chính — màu Cam vũ trụ)
2. `deep-blue.webp`, `silver.webp`
3. `view-1.webp`, `view-2.webp` (góc chụp gallery)

Đặt ảnh chính, sắp xếp thứ tự → **lưu**.

**Nói:** *Upload qua AJAX, validate MIME/kích thước; ảnh lưu `storage/app/public`.*

#### Bước D — Thêm biến thể (1 phút)

**Biến thể** → nhập nhanh 1–2 SKU từ `product.json` → `suggested_variants`:

| SKU | Màu | Dung lượng | Giá bán | Tồn |
| --- | --- | --- | --- | --- |
| IP17P-COS-256 | Cam vũ trụ | 256 GB | 35.790.000 | 12 |
| IP17P-SIL-256 | Bạc | 256 GB | 35.790.000 | 10 |

**Lưu** → mở trang khách `/products/iphone-17-pro` (nếu đã bật) hoặc tìm trong danh sách.

**Nói:** *Một sản phẩm — nhiều biến thể SKU; giá và kho quản lý độc lập.*

---

### Phần 5 — Admin: Đơn hàng & Dashboard (2 phút)

1. **Đơn hàng** → mở đơn vừa đặt → đổi trạng thái **Đã xác nhận** → **Đang giao**.
2. **Dashboard**: số SP, khách, đơn chờ, doanh thu, **biến thể sắp hết hàng**.
3. (Tuỳ chọn) **Hủy đơn** trên đơn test → **hoàn tồn kho**.

**Nói:** *Middleware `admin` + `canAccessAdmin()`; đổi trạng thái có validation theo state machine.*

---

### Phần 6 — Kết luận (1 phút)

**Nói:**

> Tóm lại, iStore hoàn thành luồng mua hàng end-to-end: catalog đa biến thể, giỏ AJAX, checkout COD có transaction, quản trị đầy đủ.
>
> Nhóm có **220+ feature test** PHPUnit (auth, giỏ, đơn hàng, admin).
>
> Cảm ơn thầy/cô đã lắng nghe, em sẵn sàng trả lời câu hỏi.

---

## 3. Checklist trước giờ G

- [ ] `php artisan test` pass
- [ ] Ảnh seed hiển thị (`storage:link`)
- [ ] Đã thử thêm 1 SP từ `demo/products/` ít nhất một lần
- [ ] Biết đường dẫn admin và tài khoản demo
- [ ] Tắt thông báo OS / đóng tab không liên quan
- [ ] Phân công: **1 người thuyết trình**, **1 người thao tác chuột**

---

## 4. Câu hỏi thầy hay hỏi — gợi ý trả lời ngắn

| Câu hỏi | Gợi ý trả lời |
| --- | --- |
| Vì sao dùng Blade SSR thay vì React? | Phù hợp đồ án web PHP, SEO đơn giản, giảm phức tạp; chỉ AJAX chỗ cần. |
| Giỏ hàng lưu ở đâu? | Session Laravel, key theo user/guest. |
| Trừ tồn kho khi nào? | Khi đặt hàng thành công trong transaction. |
| Phân quyền admin? | Cột `role` + middleware `admin`. |
| Bảo mật form? | CSRF, validation FormRequest, mass assignment `$fillable`. |
| Ảnh upload? | Validate type/size, lưu disk `public`, không hotlink ngoài. |

---

## 5. Phân công gợi ý trong nhóm

| Thành viên | Nhiệm vụ demo |
| --- | --- |
| A | Mở đầu + luồng khách (phần 1–2) |
| B | Checkout + đơn hàng khách (phần 3) |
| C | Live thêm sản phẩm iPhone 17 (phần 4) |
| D | Admin đơn hàng + dashboard + kết (phần 5–6) |

---

*Tài nguyên ảnh/mô tả tham khảo từ Cellphones — chỉ phục vụ demo học tập, không dùng cho mục đích thương mại.*
