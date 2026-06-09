# ĐẶC TẢ ĐỒ ÁN WEBSITE BÁN ĐIỆN THOẠI APPLE

## 1. Thông tin đề tài

### 1.1. Tên đề tài

Xây dựng website bán điện thoại di động chuyên Apple.

Tên thương hiệu sử dụng trong đồ án có thể là `iStore`, `Apple Phone Store` hoặc một tên trung tính khác. Đây là website phục vụ mục đích học tập, không tự nhận là cửa hàng Apple chính thức.

### 1.2. Môn học

Internet và Công nghệ Web.

### 1.3. Mô tả

Hệ thống là một website thương mại điện tử quy mô nhỏ chuyên bán điện thoại iPhone. Website cho phép khách hàng tìm kiếm, lọc, xem sản phẩm, chọn biến thể màu sắc và dung lượng, thêm vào giỏ hàng, đặt hàng COD và theo dõi đơn hàng.

Quản trị viên có thể quản lý danh mục, dòng sản phẩm, sản phẩm, hình ảnh, biến thể, tồn kho, khách hàng và đơn hàng.

Website sử dụng server-side rendering bằng Laravel Blade. Tailwind CSS phụ trách giao diện. jQuery chỉ hỗ trợ các tương tác cần thiết như chọn biến thể, cập nhật giỏ hàng, xác nhận thao tác và nâng cấp trải nghiệm lọc sản phẩm.

## 2. Mục tiêu

### 2.1. Mục tiêu nghiệp vụ

Hệ thống phải hỗ trợ luồng cơ bản:

1. Khách hàng truy cập website.
2. Khách hàng tìm kiếm hoặc lọc sản phẩm.
3. Khách hàng xem chi tiết sản phẩm.
4. Khách hàng chọn màu và dung lượng.
5. Khách hàng thêm biến thể vào giỏ hàng.
6. Khách hàng đăng nhập.
7. Khách hàng nhập thông tin nhận hàng.
8. Hệ thống tạo đơn COD và trừ tồn kho.
9. Quản trị viên xử lý trạng thái đơn.
10. Khách hàng xem trạng thái đơn hàng.

### 2.2. Mục tiêu kỹ thuật

Đồ án phải thể hiện:

- PHP và Laravel.
- MySQL và mô hình dữ liệu quan hệ.
- Server-side rendering.
- MVC.
- Session và cookie.
- Authentication và authorization.
- Form validation.
- CRUD.
- Tìm kiếm, lọc, sắp xếp và phân trang.
- AJAX bằng jQuery ở mức vừa phải.
- Database transaction.
- Xử lý tồn kho.
- Bảo mật web cơ bản.
- Responsive design.
- Kiểm thử feature.

## 3. Phạm vi

### 3.1. Chức năng bắt buộc cho khách hàng

- Xem trang chủ.
- Xem danh sách sản phẩm.
- Tìm kiếm sản phẩm.
- Lọc sản phẩm.
- Sắp xếp sản phẩm.
- Phân trang sản phẩm.
- Xem chi tiết sản phẩm.
- Chọn màu sắc và dung lượng.
- Đăng ký.
- Đăng nhập.
- Đăng xuất.
- Ghi nhớ đăng nhập, nếu nhóm còn thời gian.
- Xem và cập nhật hồ sơ.
- Đổi mật khẩu.
- Thêm sản phẩm vào giỏ hàng.
- Cập nhật số lượng giỏ hàng.
- Xóa sản phẩm khỏi giỏ hàng.
- Đặt hàng COD.
- Xem lịch sử đơn hàng.
- Xem chi tiết đơn hàng.

### 3.2. Chức năng bắt buộc cho quản trị viên

- Đăng nhập bằng tài khoản có vai trò admin.
- Xem dashboard cơ bản.
- Quản lý danh mục.
- Quản lý dòng sản phẩm.
- Quản lý màu sắc.
- Quản lý tùy chọn dung lượng.
- Quản lý sản phẩm.
- Quản lý hình ảnh sản phẩm.
- Quản lý biến thể.
- Quản lý giá và tồn kho.
- Quản lý đơn hàng.
- Cập nhật trạng thái đơn hàng.
- Xem danh sách khách hàng.
- Khóa hoặc mở khóa tài khoản khách hàng.

### 3.3. Ngoài phạm vi

Không triển khai trong MVP:

- Đánh giá sản phẩm.
- Bình luận sản phẩm.
- Phản hồi bình luận.
- Thanh toán trực tuyến.
- Mã giảm giá.
- Tích điểm.
- Danh sách yêu thích.
- Chat.
- Social login.
- Tích hợp đơn vị vận chuyển.
- Theo dõi giao hàng thời gian thực.
- Nhiều nhà bán hàng.
- Nhiều kho.
- Elasticsearch, Meilisearch hoặc dịch vụ tìm kiếm ngoài.
- Gợi ý sản phẩm bằng AI.
- Thuộc tính sản phẩm động kiểu EAV.

## 4. Công nghệ

| Thành phần | Công nghệ |
|---|---|
| Backend | PHP, Laravel |
| Database | MySQL |
| ORM | Eloquent |
| Rendering | Laravel Blade |
| CSS | Tailwind CSS |
| JavaScript | jQuery |
| Bundler | Vite |
| Authentication | Laravel session authentication |
| Testing | PHPUnit hoặc Pest |
| Source control | Git, GitHub |
| Development IDE | Cursor |

Khuyến nghị dùng một phiên bản Laravel ổn định đã được khóa trong `composer.json` và `composer.lock`. Không nâng cấp framework giữa quá trình làm tính năng.

## 5. Actor

### 5.1. Khách chưa đăng nhập

Có thể:

- Xem trang chủ.
- Xem sản phẩm.
- Tìm kiếm, lọc và sắp xếp.
- Xem chi tiết sản phẩm.
- Thêm sản phẩm vào giỏ hàng.
- Cập nhật giỏ hàng.
- Đăng ký.
- Đăng nhập.

Không thể đặt hàng cho đến khi đăng nhập.

### 5.2. Khách hàng

Có toàn bộ quyền của khách chưa đăng nhập và có thể:

- Checkout.
- Xem đơn hàng của chính mình.
- Cập nhật hồ sơ.
- Đổi mật khẩu.

### 5.3. Quản trị viên

Có thể truy cập khu vực `/admin` và quản lý dữ liệu hệ thống.

Admin không dùng chung giao diện nghiệp vụ với khách hàng, nhưng vẫn dùng cùng bảng `users` và trường `role`.

## 6. Module chức năng

### 6.1. Authentication

#### Đăng ký

Thông tin:

- Họ tên.
- Email.
- Số điện thoại.
- Mật khẩu.
- Xác nhận mật khẩu.

Quy tắc:

- Email duy nhất.
- Số điện thoại duy nhất.
- Mật khẩu tối thiểu 8 ký tự.
- Mật khẩu được hash.
- Tài khoản mới có role `customer`.
- Client không được gửi role.

#### Đăng nhập

- Đăng nhập bằng email và mật khẩu.
- Từ chối tài khoản bị khóa.
- Regenerate session sau đăng nhập.
- Chuyển về URL dự định truy cập hoặc trang chủ.
- Có thể bổ sung checkbox `Ghi nhớ đăng nhập`.

#### Đăng xuất

- Dùng POST.
- Invalidate session.
- Regenerate CSRF token.

### 6.2. Hồ sơ

Khách hàng có thể:

- Cập nhật họ tên.
- Cập nhật số điện thoại.
- Cập nhật địa chỉ mặc định.
- Đổi mật khẩu.

Đổi mật khẩu yêu cầu mật khẩu hiện tại.

### 6.3. Catalog

#### Danh mục

Dùng để phân nhóm sản phẩm và giữ khả năng mở rộng.

Ví dụ:

- iPhone.
- Phụ kiện.

MVP tập trung vào iPhone.

#### Dòng sản phẩm

Ví dụ:

- iPhone 13 Series.
- iPhone 14 Series.
- iPhone 15 Series.
- iPhone 16 Series.

#### Sản phẩm

Sản phẩm là model chung, ví dụ `iPhone 16 Pro`.

Thông tin:

- Danh mục.
- Dòng sản phẩm.
- Tên.
- Slug.
- Năm ra mắt.
- Mô tả ngắn.
- Mô tả chi tiết.
- Thông số.
- Sản phẩm nổi bật.
- Trạng thái hoạt động.

#### Hình ảnh

Một sản phẩm có nhiều hình ảnh.

Mỗi hình có:

- Đường dẫn.
- Alt text.
- Thứ tự.
- Trạng thái ảnh chính.

#### Biến thể

Biến thể là đơn vị bán và quản lý tồn kho.

Ví dụ:

- iPhone 16 Pro / Black / 128 GB.
- iPhone 16 Pro / Black / 256 GB.
- iPhone 16 Pro / Desert Titanium / 256 GB.

Thông tin:

- Sản phẩm.
- Màu sắc.
- Dung lượng.
- SKU.
- Giá niêm yết.
- Giá bán.
- Số lượng tồn kho.
- Trạng thái hoạt động.

## 7. Tìm kiếm, lọc, sắp xếp và phân trang

### 7.1. Nguyên tắc chung

Tất cả trạng thái phải nằm trên URL query string.

Ví dụ:

    /products?q=iphone+16&series=iphone-16&colors[]=black&storages[]=256&min_price=15000000&max_price=35000000&in_stock=1&sort=price_asc&page=2

Lợi ích:

- Có thể bookmark và chia sẻ.
- Reload không mất bộ lọc.
- Back và Forward hoạt động đúng.
- Phân trang giữ nguyên điều kiện.
- SSR không phụ thuộc JavaScript.

### 7.2. Tham số được hỗ trợ

| Tham số | Kiểu | Ý nghĩa |
|---|---|---|
| `q` | string | Từ khóa |
| `category` | slug | Danh mục |
| `series` | slug | Dòng sản phẩm |
| `colors[]` | slug array | Một hoặc nhiều màu |
| `storages[]` | integer array | Một hoặc nhiều mức dung lượng GB |
| `min_price` | integer | Giá tối thiểu |
| `max_price` | integer | Giá tối đa |
| `in_stock` | boolean | Chỉ sản phẩm còn hàng |
| `featured` | boolean | Chỉ sản phẩm nổi bật |
| `sort` | enum | Cách sắp xếp |
| `page` | integer | Trang hiện tại |

### 7.3. Tìm kiếm

Tìm theo:

- Tên sản phẩm.
- Tên dòng sản phẩm.
- Mô tả ngắn.
- SKU.

MVP dùng MySQL và Eloquent. Có thể dùng `LIKE` với dữ liệu nhỏ.

Không thêm dịch vụ search bên ngoài.

### 7.4. Lọc

Cho phép kết hợp:

- Danh mục.
- Dòng sản phẩm.
- Màu.
- Dung lượng.
- Khoảng giá.
- Còn hàng.
- Nổi bật.

Quy tắc:

- Một sản phẩm được trả về nếu có ít nhất một biến thể đang hoạt động phù hợp.
- Lọc giá dựa trên `sale_price` của biến thể đang hoạt động.
- Lọc còn hàng yêu cầu ít nhất một biến thể phù hợp có `stock_quantity > 0`.
- Không trả về sản phẩm bị ẩn hoặc soft delete.
- Không tạo bản ghi sản phẩm trùng khi join nhiều biến thể.

### 7.5. Sắp xếp

Giá trị hợp lệ:

| Giá trị | Ý nghĩa |
|---|---|
| `newest` | Mới nhất |
| `price_asc` | Giá thấp đến cao |
| `price_desc` | Giá cao đến thấp |
| `name_asc` | Tên A đến Z |
| `name_desc` | Tên Z đến A |

Mặc định là `newest`.

Không chấp nhận tên cột tùy ý từ client.

### 7.6. Giá hiển thị

Product card hiển thị giá thấp nhất trong các biến thể đang hoạt động.

Nếu các biến thể có nhiều mức giá:

    Từ 19.990.000 ₫

Nếu không có biến thể hoạt động, sản phẩm không xuất hiện công khai.

### 7.7. Phân trang

- 12 sản phẩm mỗi trang.
- Giữ nguyên query string khi chuyển trang.
- Khi thay đổi filter, trở về page 1.
- Không cho client tùy ý tăng page size trong MVP.

### 7.8. jQuery progressive enhancement

Bản SSR là luồng chuẩn:

1. Người dùng chọn filter.
2. Gửi GET form.
3. Server render lại trang.

Có thể nâng cấp bằng jQuery:

- Tự submit sau khi đổi filter.
- Debounce ô tìm kiếm.
- Hiển thị loading.
- Tải partial HTML.
- Cập nhật URL bằng History API.

Nếu JavaScript lỗi, form GET vẫn phải hoạt động.

Không viết lại logic lọc trong JavaScript.

## 8. Trang chủ

Hiển thị:

- Header.
- Thanh tìm kiếm.
- Hero hoặc banner đơn giản.
- Dòng sản phẩm.
- Sản phẩm nổi bật.
- Sản phẩm mới.
- Chính sách giao hàng và bảo hành.
- Footer.

Không cần carousel phức tạp.

## 9. Chi tiết sản phẩm

Hiển thị:

- Tên.
- Breadcrumb.
- Gallery ảnh.
- Mô tả ngắn.
- Giá theo biến thể.
- Màu.
- Dung lượng.
- Tồn kho.
- Mô tả chi tiết.
- Thông số.
- Nút thêm vào giỏ.

Quy tắc:

- Người dùng phải chọn một biến thể hợp lệ.
- Thay đổi màu hoặc dung lượng cập nhật giá và tồn kho.
- Không cho thêm biến thể hết hàng.
- Server vẫn kiểm tra lại khi nhận request.

## 9.1. Chiến lược hình ảnh

Hình ảnh được triển khai theo ba giai đoạn:

1. Placeholder SVG cục bộ trong Phase 0.
2. Ảnh demo cục bộ cho seed data sau khi giao diện ổn định.
3. Upload ảnh thật khi triển khai admin product management.

Quy tắc:

- Không dùng URL ảnh bên ngoài làm nguồn chạy chính.
- Database chỉ lưu path tương đối.
- Product không có ảnh phải dùng placeholder.
- Thứ tự fallback là ảnh chính, ảnh đầu tiên, placeholder.
- Product card dùng tỷ lệ vuông và `object-contain`.
- Trang danh sách phải tránh N+1 query.
- Admin upload chỉ nhận JPEG, PNG hoặc WebP.
- Không upload SVG từ admin trong MVP.
- Không bắt buộc package xử lý ảnh.
- Xem chi tiết tại `docs/IMAGE_STRATEGY.md`.

## 10. Giỏ hàng

### 10.1. Lưu trữ

Lưu trong session.

Cấu trúc khuyến nghị:

    [
        15 => [
            'variant_id' => 15,
            'quantity' => 2,
        ],
    ]

Chỉ lưu ID biến thể và số lượng.

### 10.2. Chức năng

- Xem giỏ hàng.
- Thêm biến thể.
- Tăng hoặc giảm số lượng.
- Nhập số lượng.
- Xóa một dòng.
- Xóa toàn bộ.
- Xem tạm tính.
- Chuyển đến checkout.

### 10.3. Quy tắc

- Một biến thể chỉ có một dòng.
- Thêm lại cùng biến thể sẽ cộng số lượng.
- Số lượng là số nguyên dương.
- Không vượt tồn kho.
- Giá lấy lại từ database.
- Biến thể bị tắt vẫn có thể hiển thị cảnh báo trong giỏ nhưng không thể checkout.

## 11. Checkout

### 11.1. Điều kiện

- Người dùng đã đăng nhập.
- Giỏ hàng không rỗng.
- Có ít nhất một sản phẩm hợp lệ.

### 11.2. Thông tin nhận hàng

- Họ tên.
- Số điện thoại.
- Tỉnh hoặc thành phố.
- Quận hoặc huyện.
- Phường hoặc xã.
- Địa chỉ chi tiết.
- Ghi chú, không bắt buộc.

### 11.3. Thanh toán

Chỉ có COD.

Phí vận chuyển trong MVP:

- Có thể bằng 0.
- Hoặc một mức cố định cấu hình phía server.

### 11.4. Transaction

Quy trình bắt buộc:

1. Validation.
2. Bắt đầu transaction.
3. Lấy và khóa các variant cần mua.
4. Kiểm tra lại trạng thái.
5. Kiểm tra lại tồn kho.
6. Tính giá phía server.
7. Tạo order.
8. Tạo order items dạng snapshot.
9. Trừ tồn kho.
10. Commit.
11. Xóa cart.
12. Redirect đến trang thành công.

Nếu lỗi, rollback toàn bộ.

## 12. Đơn hàng

### 12.1. Trạng thái

- `pending`: Chờ xác nhận.
- `confirmed`: Đã xác nhận.
- `shipping`: Đang giao.
- `completed`: Hoàn thành.
- `cancelled`: Đã hủy.

Luồng hợp lệ:

    pending -> confirmed -> shipping -> completed
    pending -> cancelled
    confirmed -> cancelled

Không chuyển đơn `completed` hoặc `cancelled` sang trạng thái khác.

### 12.2. Hủy đơn

MVP cho admin hủy.

Khi hủy:

- Chỉ hủy từ `pending` hoặc `confirmed`.
- Hoàn tồn kho đúng một lần.
- Ghi `cancelled_at`.
- Thao tác nằm trong transaction.

### 12.3. Khách hàng

Khách hàng có thể:

- Xem danh sách đơn của chính mình.
- Xem chi tiết.
- Không xem đơn của người khác.
- Không tự đổi trạng thái.

## 13. Admin

### 13.1. Dashboard

Hiển thị:

- Tổng sản phẩm đang hoạt động.
- Tổng khách hàng.
- Tổng đơn.
- Đơn chờ xác nhận.
- Doanh thu từ đơn hoàn thành.
- Đơn mới nhất.
- Biến thể sắp hết hàng.

Không bắt buộc biểu đồ.

### 13.2. Catalog management

Admin quản lý:

- Category.
- Product series.
- Color.
- Storage option.
- Product.
- Product images.
- Product variants.

Quy tắc:

- Không xóa cứng dữ liệu đã được order item tham chiếu.
- Ưu tiên `is_active` hoặc soft delete.
- SKU duy nhất.
- Slug duy nhất.
- Giá không âm.
- Tồn kho không âm.
- Giá bán không lớn hơn giá niêm yết khi giá niêm yết được nhập.

### 13.3. Order management

Admin có thể:

- Tìm theo mã đơn.
- Lọc theo trạng thái.
- Lọc theo khoảng ngày.
- Xem chi tiết.
- Cập nhật trạng thái hợp lệ.
- Hủy và hoàn tồn kho.

### 13.4. Customer management

Admin có thể:

- Xem danh sách.
- Tìm theo tên, email, số điện thoại.
- Xem thông tin.
- Khóa hoặc mở khóa.

Admin không được xem mật khẩu.

## 14. Yêu cầu bảo mật

- CSRF cho mọi request thay đổi dữ liệu.
- Escape output bằng Blade.
- Prepared statements hoặc Eloquent.
- Password hashing.
- Regenerate session sau login.
- POST logout.
- Middleware cho admin.
- Policy cho order.
- Validation và authorization phía server.
- Không tin giá, tồn kho, tổng tiền hoặc role từ client.
- Kiểm tra MIME, extension và kích thước ảnh.
- Tạo tên file upload mới.
- Không cho upload file thực thi.
- Không commit `.env`.
- Rate limit login.
- Không dùng raw HTML cho dữ liệu người dùng.

## 15. Yêu cầu giao diện

- Tiếng Việt.
- Tối giản và hiện đại.
- Nền sáng.
- Màu trung tính.
- Màu nhấn xanh.
- Responsive mobile-first.
- Card sản phẩm đồng nhất.
- Có empty state.
- Có trạng thái loading và lỗi.
- Form có label và validation message.
- Khu vực admin ưu tiên desktop nhưng vẫn dùng được trên tablet.

## 16. Yêu cầu phi chức năng

### 16.1. Hiệu năng

- Pagination.
- Eager loading.
- Index cho khóa ngoại và trường tìm kiếm quan trọng.
- Không N+1.
- Tối ưu kích thước ảnh.
- Không truy vấn toàn bộ bảng để lọc trong PHP.

### 16.2. Bảo trì

- Thin controller.
- Form Request.
- Policy.
- Query object cho product discovery.
- Service hoặc action cho checkout và chuyển trạng thái.
- Blade component cho UI lặp.
- Không đặt SQL hoặc nghiệp vụ trong Blade.

### 16.3. Khả năng sử dụng

- Thông báo bằng tiếng Việt.
- Giữ old input khi validation lỗi.
- Xác nhận thao tác nguy hiểm.
- Hiển thị trạng thái rõ ràng.
- Không mất filter khi phân trang.

## 17. Tiêu chí nghiệm thu

### 17.1. Search và filter

- Tìm theo từ khóa hoạt động.
- Kết hợp nhiều filter hoạt động.
- Sort giữ filter.
- Pagination giữ filter.
- URL có thể chia sẻ.
- Query không hợp lệ không gây lỗi server.
- Không có duplicate product.
- Empty state đúng.

### 17.2. Cart và checkout

- Không thêm vượt tồn kho.
- Không checkout giỏ rỗng.
- Không sửa giá từ trình duyệt.
- Order dùng giá server.
- Transaction rollback khi lỗi.
- Tồn kho giảm đúng.
- Hủy đơn hoàn tồn kho đúng một lần.

### 17.3. Authorization

- Customer không vào admin.
- Customer không xem order của người khác.
- Tài khoản bị khóa không đăng nhập.
- Public request không tự gán role.

### 17.4. Admin

- CRUD catalog hoạt động.
- Upload ảnh hợp lệ và lưu đường dẫn tương đối.
- Sản phẩm không có ảnh sử dụng placeholder cục bộ.
- Không có external image hotlink.
- Quản lý variant và stock.
- Chuyển trạng thái đúng luồng.
- Dashboard hiển thị số liệu cơ bản.

## 18. Kịch bản demo

1. Mở trang chủ.
2. Tìm `iPhone 16`.
3. Lọc 256 GB, một màu và khoảng giá.
4. Sắp xếp giá tăng dần.
5. Mở sản phẩm.
6. Chọn biến thể.
7. Thêm vào giỏ bằng jQuery.
8. Cập nhật số lượng.
9. Đăng nhập.
10. Checkout COD.
11. Xem đơn vừa tạo.
12. Đăng nhập admin.
13. Xem dashboard.
14. Xác nhận đơn.
15. Cập nhật sang đang giao.
16. Kiểm tra trạng thái phía khách hàng.

## 19. Thứ tự ưu tiên

### Must have

- Authentication.
- Catalog.
- Search/filter/sort/pagination.
- Product detail.
- Session cart.
- COD checkout.
- Order history.
- Admin catalog.
- Admin orders.
- Authorization.
- Tests quan trọng.

### Should have

- AJAX cart.
- jQuery filter enhancement.
- Product image gallery.
- Admin dashboard.
- Remember me.

### Could have

- Export order CSV.
- Biểu đồ doanh thu.
- Customer self-cancel pending order.

Các mục `Could have` chỉ làm sau khi toàn bộ `Must have` ổn định.
