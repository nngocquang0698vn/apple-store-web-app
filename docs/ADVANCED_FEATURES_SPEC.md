# ĐẶC TẢ CÁC TÍNH NĂNG NÂNG CAO

## 1. Mục đích tài liệu

Tài liệu này mô tả các phase nâng cao được triển khai sau khi hệ thống đã hoàn thành toàn bộ chức năng MVP, gồm:

- Authentication.
- Catalog sản phẩm.
- Tìm kiếm, lọc, sắp xếp và phân trang.
- Product detail.
- Session cart.
- Checkout COD.
- Customer order history.
- Admin catalog.
- Admin order management.
- Dynamic UI bằng jQuery.
- Responsive cơ bản.
- Kiểm thử các nghiệp vụ chính.

Mục tiêu:

1. Tăng chất lượng giao diện quản trị.
2. Tăng trải nghiệm mua sắm.
3. Bổ sung nghiệp vụ thương mại điện tử dễ trình bày.
4. Ưu tiên tính năng dễ làm và dễ ghi điểm.
5. Giữ Laravel Blade SSR làm nền tảng.
6. Dùng jQuery cho progressive enhancement.
7. Chỉ thêm thư viện mới sau khi human phê duyệt.

---

# 2. Nguyên tắc triển khai

## 2.1. Mỗi phase phải hoàn chỉnh

Mỗi phase cần có:

- Phân tích yêu cầu.
- Migration nếu cần.
- Model relationship.
- Form Request.
- Authorization.
- Backend logic.
- Blade UI.
- jQuery enhancement nếu phù hợp.
- Feature test.
- Cập nhật tài liệu.

## 2.2. Server là nguồn dữ liệu tin cậy

JavaScript chỉ hỗ trợ trải nghiệm động. Server vẫn quyết định:

- Giá.
- Tồn kho.
- Tổng tiền.
- Trạng thái đơn hàng.
- Điều kiện được review.
- Mức giảm giá.
- Dữ liệu báo cáo.
- Nội dung email.

## 2.3. Chính sách thư viện mới

Cursor có thể đề xuất thư viện mới nhưng không được tự cài.

Đề xuất phải nêu:

- Vấn đề cần giải quyết.
- Phương án không dùng thư viện.
- Tên package.
- Version.
- License.
- Bundle impact.
- Maintenance impact.
- File bị ảnh hưởng.
- Kế hoạch gỡ bỏ hoặc thay thế.

Chỉ cài sau khi human xác nhận.

---

# 3. Thứ tự phase đề xuất

| Phase | Tính năng | Độ khó | Khả năng ghi điểm |
|---:|---|---|---|
| 12 | WYSIWYG product description | Thấp | Cao |
| 13 | Wishlist | Thấp | Cao |
| 14 | Khách tự hủy đơn pending | Thấp đến trung bình | Cao |
| 15 | Low-stock alert | Thấp | Cao |
| 16 | Product review | Trung bình | Rất cao |
| 17 | Related products và recently viewed | Thấp | Trung bình |
| 18 | Revenue report và CSV export | Trung bình | Cao |
| 19 | Coupon cơ bản | Trung bình | Rất cao |
| 20 | Admin audit log | Trung bình | Cao |
| 21 | Transactional email | Trung bình | Rất cao |

Email được đặt ở phase cuối vì cần external service, queue worker, domain verification hoặc SMTP/API credentials.

---

# Phase 12 — WYSIWYG Product Description

## 12.1. Mục tiêu

Cho phép admin chỉnh sửa mô tả chi tiết sản phẩm bằng rich-text editor thay vì nhập plain text hoặc HTML thủ công.

## 12.2. Audit bắt buộc

Kiểm tra:

- Form create product.
- Form edit product.
- Field `description`.
- JavaScript đang áp dụng.
- Cách lưu dữ liệu.
- Cách render ở product detail.
- Package editor hiện có.
- Toolbar có xuất hiện không.

Chưa hỗ trợ WYSIWYG khi:

- Chỉ có textarea.
- Không có toolbar.
- Admin phải nhập HTML.
- Nội dung không giữ heading, list, bold hoặc link.

Đã hỗ trợ khi:

- Có editor JavaScript.
- Có toolbar.
- Edit form load lại nội dung cũ.
- Nội dung được đồng bộ trước submit.
- Server sanitize nội dung.
- Customer page render HTML an toàn.

## 12.3. Library đề xuất

Khuyến nghị dùng Quill 2.

Lý do:

- Không yêu cầu React hoặc Vue.
- Phù hợp Laravel Blade.
- Dễ tích hợp Vite.
- Đủ tính năng cho mô tả sản phẩm.
- Gọn hơn editor doanh nghiệp lớn.

Package dự kiến:

    quill

Không dùng CDN. Chỉ cài sau khi human xác nhận.

## 12.4. Cấu trúc frontend

Module đề xuất:

    resources/js/admin/product-editor.js

Blade liên quan:

    resources/views/admin/products/create.blade.php
    resources/views/admin/products/edit.blade.php

Có thể tạo component:

    resources/views/components/admin/rich-text-editor.blade.php

## 12.5. Toolbar MVP

Cho phép:

- Heading 2.
- Heading 3.
- Bold.
- Italic.
- Ordered list.
- Bullet list.
- Block quote.
- Link.
- Clear formatting.

Không bật:

- iframe.
- Video embed.
- Raw HTML.
- JavaScript.
- Custom CSS.
- File upload.
- Inline image upload.
- Table phức tạp.

Thông số kỹ thuật nên tiếp tục dùng field hoặc cấu trúc riêng.

## 12.6. Lưu dữ liệu

Phương án đơn giản:

- Tiếp tục dùng `products.description`.
- Kiểu `LONGTEXT`.
- Lưu HTML đã sanitize.

Có thể đổi tên thành `description_html`, nhưng không bắt buộc.

Không cần lưu đồng thời Delta JSON và HTML trong scope đồ án.

## 12.7. Security

Whitelist tag:

- `p`
- `br`
- `h2`
- `h3`
- `strong`
- `em`
- `ul`
- `ol`
- `li`
- `blockquote`
- `a`

Whitelist attribute:

- `href`
- `title`
- `target`
- `rel`

Loại bỏ:

- `script`
- `style`
- `iframe`
- `object`
- `embed`
- `javascript:`
- Event handler như `onclick`
- CSS tùy ý

Chỉ dùng raw Blade output sau khi dữ liệu đã sanitize.

## 12.8. Test cases

- Admin tạo description có heading.
- Admin tạo description có list.
- Admin sửa description.
- Nội dung cũ load đúng.
- Script bị loại bỏ.
- Event handler bị loại bỏ.
- Link nguy hiểm bị loại bỏ.
- Customer xem đúng format.
- Customer không truy cập editor admin.

## 12.9. Acceptance criteria

- Admin không cần viết HTML.
- Create và edit dùng cùng editor.
- Nội dung cũ load đúng.
- HTML nguy hiểm bị sanitize.
- Product detail responsive.
- Không phá dữ liệu cũ.

---

# Phase 13 — Wishlist

## 13.1. Mục tiêu

Cho phép khách hàng lưu sản phẩm yêu thích để xem hoặc mua sau.

## 13.2. Chức năng

- Thêm wishlist.
- Xóa wishlist.
- Xem danh sách wishlist.
- Heart icon trên product card.
- Heart icon trên product detail.
- jQuery cập nhật không reload.
- Guest được yêu cầu đăng nhập.

## 13.3. Database

Bảng `wishlists`:

- `id`
- `user_id`
- `product_id`
- `created_at`
- `updated_at`

Unique:

    user_id + product_id

## 13.4. Routes

    GET    /account/wishlist
    POST   /wishlist/items
    DELETE /wishlist/items/{product}

## 13.5. Quy tắc

- Chỉ user đăng nhập.
- Không duplicate.
- Product phải tồn tại.
- Product bị ẩn không thể thêm mới.
- User chỉ xem wishlist của mình.

## 13.6. Dynamic UI

jQuery:

- Toggle heart.
- Update icon.
- Loading state.
- Success và error state.
- Giữ SSR fallback.

Font Awesome:

- `fa-heart`
- `fa-regular fa-heart`

## 13.7. Test cases

- Guest không thể thêm.
- User thêm thành công.
- Duplicate bị ngăn.
- User xóa thành công.
- User không tác động wishlist người khác.
- Product inactive không thêm được.
- JSON response đúng.
- SSR fallback đúng.

---

# Phase 14 — Khách Tự Hủy Đơn Pending

## 14.1. Mục tiêu

Cho phép khách hàng tự hủy đơn khi đơn chưa được admin xác nhận.

## 14.2. Quy tắc

- User phải là owner.
- Chỉ `pending`.
- Yêu cầu lý do hủy.
- Dùng transaction.
- Hoàn tồn kho đúng một lần.
- Không hủy `confirmed`, `shipping`, `completed`, `cancelled`.
- Ghi actor hủy.

## 14.3. Database bổ sung

Có thể thêm vào `orders`:

- `cancelled_by`
- `cancel_reason`
- `cancelled_at`

`cancelled_by` có thể là:

- `customer`
- `admin`

## 14.4. Backend

Dùng chung action:

    CancelOrder

Action:

1. Kiểm tra quyền.
2. Kiểm tra trạng thái.
3. Lock order.
4. Lock variant.
5. Restock.
6. Update status.
7. Ghi reason.
8. Commit.

## 14.5. UI

- Nút hủy chỉ hiện khi pending.
- Modal xác nhận.
- Textarea lý do.
- Loading state.
- Thông báo kết quả.

jQuery có thể submit AJAX nhưng phải có form fallback.

## 14.6. Test cases

- Owner hủy pending thành công.
- Người khác không hủy được.
- Confirmed không hủy được.
- Completed không hủy được.
- Inventory được hoàn.
- Không hoàn tồn kho hai lần.
- Reason được lưu.

---

# Phase 15 — Low-Stock Alert

## 15.1. Mục tiêu

Giúp admin biết variant nào sắp hết hàng.

## 15.2. Chức năng

- Threshold cấu hình.
- Dashboard hiển thị low-stock variants.
- Badge cảnh báo.
- Filter trong admin variants.
- Sort tồn kho tăng dần.

## 15.3. Cấu hình

Có thể dùng:

    LOW_STOCK_THRESHOLD=5

MVP dùng config hoặc `.env.example`.

## 15.4. Query

Low stock:

    is_active = true
    stock_quantity > 0
    stock_quantity <= threshold

Out of stock:

    stock_quantity = 0

## 15.5. UI

Dashboard:

- Tổng variant low stock.
- Top 10 variant.
- Link đến inventory.

Admin table:

- Badge `Sắp hết`.
- Badge `Hết hàng`.

## 15.6. Test cases

- Stock bằng threshold được cảnh báo.
- Stock lớn hơn threshold không cảnh báo.
- Stock 0 là hết hàng.
- Variant inactive không cảnh báo.
- Dashboard count đúng.

---

# Phase 16 — Product Review

## 16.1. Mục tiêu

Cho phép khách hàng đánh giá sản phẩm đã mua.

## 16.2. Quy tắc

- Phải đăng nhập.
- Phải từng mua product.
- Order phải `completed`.
- Một user chỉ có một review trên một product.
- Rating 1 đến 5.
- User được sửa review của mình.
- User không sửa review người khác.
- Review mới là `pending`.
- Admin duyệt trước khi public.
- Chỉ `approved` được tính average.

## 16.3. Database

Bảng `reviews`:

- `id`
- `user_id`
- `product_id`
- `order_item_id`
- `rating`
- `title`
- `content`
- `status`
- `is_verified_purchase`
- `moderated_by`
- `moderated_at`
- `created_at`
- `updated_at`
- `deleted_at`

Unique:

    user_id + product_id

Status:

- `pending`
- `approved`
- `rejected`
- `hidden`

## 16.4. Verified purchase

Server kiểm tra:

    orders.user_id = current user
    orders.status = completed
    order_items.product_id = product

Không tin `order_item_id` từ client.

## 16.5. Routes

Customer:

    POST   /products/{product:slug}/reviews
    PATCH  /reviews/{review}
    DELETE /reviews/{review}

Admin:

    GET    /admin/reviews
    GET    /admin/reviews/{review}
    PATCH  /admin/reviews/{review}/status

## 16.6. Product detail UI

- Average rating.
- Review count.
- Phân bố số sao.
- Danh sách review.
- Badge `Đã mua hàng`.
- Pagination.
- Form review khi đủ điều kiện.

jQuery có thể:

- Chọn sao.
- Submit động.
- Hiển thị validation.
- Load thêm review.

SSR fallback vẫn hoạt động.

## 16.7. Admin moderation

Admin có thể:

- Xem pending.
- Approve.
- Reject.
- Hide.
- Xem user và product.
- Không sửa nội dung thay user.

## 16.8. Aggregate rating

MVP tính từ approved reviews bằng query.

Product listing có thể eager load:

- `reviews_avg_rating`
- `reviews_count`

Chưa cần lưu average trong `products`.

## 16.9. Security

- Content là plain text.
- Escape bằng Blade.
- Không cho HTML.
- Giới hạn độ dài.
- Có Policy.
- User không gửi status.
- User không gửi verified flag.

## 16.10. Test cases

- Guest không review.
- Chưa mua không review.
- Order chưa completed không review.
- Verified buyer review thành công.
- Duplicate review bị từ chối.
- Rating ngoài 1–5 bị từ chối.
- Pending không public.
- Approved được tính average.
- User không sửa review người khác.
- Admin moderation đúng.

---

# Phase 17 — Related Products và Recently Viewed

## 17.1. Related products

Ưu tiên:

1. Cùng category.
2. Cùng product series.
3. Khoảng giá gần.
4. Còn hàng.
5. Active.
6. Loại trừ product hiện tại.

Hiển thị tối đa 4 đến 8 sản phẩm.

## 17.2. Recently viewed

Lưu session hoặc local storage:

- Product ID.
- Thứ tự xem.
- Giới hạn 8 đến 10.

Khuyến nghị session nếu muốn SSR.

Không dùng dữ liệu này làm nguồn nghiệp vụ.

## 17.3. Test cases

- Không chứa product hiện tại.
- Chỉ active product.
- Không duplicate.
- Giới hạn đúng.
- Recently viewed giữ thứ tự.
- Xóa product invalid khỏi session.

---

# Phase 18 — Revenue Report và CSV Export

## 18.1. Mục tiêu

Bổ sung báo cáo cho admin và tăng chất lượng demo.

## 18.2. Chức năng

- Doanh thu theo ngày.
- Doanh thu theo tháng.
- Số đơn theo trạng thái.
- Sản phẩm bán chạy.
- Danh mục bán chạy.
- Giá trị đơn trung bình.
- Export CSV theo khoảng ngày.

Chỉ tính doanh thu từ order `completed`.

## 18.3. Filters

- From date.
- To date.
- Order status.
- Category nếu cần.

## 18.4. Chart

Không thêm library:

- Table.
- Summary card.
- CSS bar đơn giản.

Nếu dùng Chart.js:

- Cursor phải tạo proposal.
- Human xác nhận trước khi cài.

## 18.5. CSV

CSV gồm:

- Order code.
- Customer.
- Completed date.
- Subtotal.
- Shipping.
- Discount nếu có.
- Total.

## 18.6. Security

- Admin only.
- Validate date range.
- Chống CSV injection.
- Escape value bắt đầu bằng `=`, `+`, `-`, `@`.

## 18.7. Test cases

- Chỉ completed được tính revenue.
- Date range đúng.
- Average order value đúng.
- Best seller đúng.
- CSV có header.
- CSV không chứa order ngoài filter.
- Customer không truy cập.

---

# Phase 19 — Coupon Cơ Bản

## 19.1. Mục tiêu

Cho phép áp dụng một mã giảm giá trong checkout.

## 19.2. Scope

Hỗ trợ:

- Giảm số tiền cố định.
- Giảm phần trăm.
- Ngày bắt đầu.
- Ngày kết thúc.
- Minimum order amount.
- Maximum discount.
- Total usage limit.
- Active status.
- Một coupon trên một order.

Không hỗ trợ:

- Coupon riêng user.
- Coupon theo category.
- Coupon stack.
- Buy one get one.
- Free shipping coupon.

## 19.3. Database

Bảng `coupons`:

- `id`
- `code`
- `type`
- `value`
- `min_order_amount`
- `max_discount_amount`
- `starts_at`
- `ends_at`
- `usage_limit`
- `used_count`
- `is_active`
- `created_at`
- `updated_at`

Bổ sung orders:

- `coupon_id`
- `coupon_code`
- `discount_amount`

## 19.4. Quy tắc

- Normalize code uppercase.
- Server validate.
- Không tin discount client.
- Final checkout tính lại.
- Lock coupon khi tăng `used_count`.
- Discount không âm.
- Total cuối không âm.

## 19.5. Dynamic UI

jQuery:

- Apply coupon.
- Remove coupon.
- Update summary từ server.
- Show error.
- Không tự tính authoritative discount.

## 19.6. Test cases

- Code hợp lệ.
- Code hết hạn.
- Code chưa bắt đầu.
- Code inactive.
- Không đủ minimum.
- Usage limit hết.
- Fixed discount.
- Percentage discount.
- Maximum discount.
- Client discount ignored.
- Checkout transaction đúng.

---

# Phase 20 — Admin Audit Log

## 20.1. Mục tiêu

Ghi lại các thao tác quản trị quan trọng.

## 20.2. Theo dõi

- Sửa giá.
- Sửa tồn kho.
- Đổi trạng thái order.
- Hủy order.
- Khóa user.
- Mở khóa user.
- Duyệt review.
- Sửa product.
- Deactivate product.

## 20.3. Database

Bảng `audit_logs`:

- `id`
- `actor_id`
- `action`
- `subject_type`
- `subject_id`
- `old_values`
- `new_values`
- `ip_address`
- `user_agent`
- `created_at`

## 20.4. Không log

- Password.
- Password hash.
- Session token.
- API key.
- Secret.
- Sensitive headers.

## 20.5. UI

- List audit log.
- Filter actor.
- Filter action.
- Filter date.
- View detail.

Audit log chỉ đọc, không sửa.

## 20.6. Test cases

- Price update tạo log.
- Stock update tạo log.
- Order status tạo log.
- Secret không bị lưu.
- Customer không truy cập.
- Admin không sửa audit log.

---

# Phase 21 — Transactional Email

## 21.1. Mục tiêu

Gửi email khi:

1. Khách đặt hàng thành công.
2. Admin xác nhận đơn.
3. Đơn chuyển sang đang giao.
4. Đơn hoàn thành.
5. Đơn bị hủy.

Đây là phase cuối vì cần:

- External mail provider.
- API key hoặc SMTP credentials.
- Domain verification.
- Queue worker.
- Cấu hình deployment.

## 21.2. Kiến trúc

Dùng:

    Domain event
    -> Queued listener hoặc queued notification
    -> Mail provider

Event:

- `OrderPlaced`
- `OrderStatusChanged`
- `OrderCancelled`

Notification:

- `OrderPlacedNotification`
- `OrderStatusChangedNotification`
- `OrderCancelledNotification`

Không gửi trực tiếp trong controller.

Không gửi đồng bộ bên trong transaction.

## 21.3. Queue

Khuyến nghị database queue.

Cấu hình:

    QUEUE_CONNECTION=database

Cần:

- Jobs table.
- Failed jobs table.
- Queue worker.

Development:

    php artisan queue:work

Nếu deployment không chạy worker được, có thể dùng sync cho demo, nhưng báo cáo vẫn nên trình bày queue architecture.

## 21.4. Transaction safety

Notification chỉ dispatch sau khi:

- Order commit thành công.
- Status transition commit thành công.
- Cancellation và restock commit thành công.

Dùng:

- `afterCommit()`
- Hoặc queue config `after_commit = true`

Không gửi email thành công nếu transaction rollback.

## 21.5. Tool theo môi trường

### Local development

Ưu tiên:

1. Mailpit.
2. Mailtrap.
3. Laravel log mailer.

Automated tests không gửi email thật.

### Demo online hoặc production nhỏ

Khuyến nghị Resend.

Lý do:

- Setup tương đối đơn giản.
- Có API.
- Phù hợp transactional email.
- Có dashboard theo dõi.
- Tích hợp tốt với Laravel.

Cần:

- API key.
- Domain.
- DNS verification.
- From address thuộc domain đã verify.

### Lựa chọn khác

Postmark:

- Transactional email tốt.
- Delivery và bounce tracking rõ.

Amazon SES:

- Phù hợp AWS.
- Phức tạp hơn.
- Cần IAM, identity verification, DKIM và production access.

Không khuyến nghị Gmail cá nhân làm mail server chính.

## 21.6. Environment variables

Ví dụ:

    MAIL_MAILER=resend
    RESEND_API_KEY=
    MAIL_FROM_ADDRESS=no-reply@example.com
    MAIL_FROM_NAME="${APP_NAME}"

Không commit credentials.

## 21.7. Nội dung email đặt hàng

- Tên khách hàng.
- Mã đơn.
- Thời gian đặt.
- Danh sách sản phẩm.
- Biến thể.
- Số lượng.
- Đơn giá VNĐ.
- Tổng tiền.
- Địa chỉ.
- COD.
- Link xem order.

## 21.8. Nội dung email trạng thái

Subject ví dụ:

- `Đơn hàng ABC123 đã được xác nhận`
- `Đơn hàng ABC123 đang được giao`
- `Đơn hàng ABC123 đã hoàn thành`
- `Đơn hàng ABC123 đã bị hủy`

## 21.9. Chống gửi trùng

Phương án đơn giản:

- Chỉ dispatch khi status thực sự thay đổi.
- Test event phát một lần.
- Job có unique key nếu cần.

Phương án nâng cao:

Bảng `order_notification_logs`:

- `id`
- `order_id`
- `notification_type`
- `order_status`
- `recipient`
- `sent_at`
- `failed_at`
- `created_at`

Không bắt buộc trong phiên bản đầu.

## 21.10. Email template

Dùng Laravel Markdown Mail hoặc Blade template.

Yêu cầu:

- Responsive.
- Tiếng Việt.
- Giá VNĐ.
- Không phụ thuộc JavaScript.
- Link rõ ràng.
- Không nhúng dữ liệu nhạy cảm.

## 21.11. Test cases

Dùng Mail fake hoặc Notification fake:

- Order success dispatch.
- Rollback không dispatch.
- Status change dispatch đúng.
- Không đổi status không gửi.
- Cancellation gửi đúng.
- Nội dung có order code.
- Nội dung có total.
- Notification queued.
- Dispatch sau commit.
- Test không gửi email thật.

## 21.12. Acceptance criteria

- Checkout không chờ mail provider.
- Không gửi nếu transaction fail.
- Không gửi duplicate.
- Template responsive.
- Local preview được.
- Provider cấu hình qua environment.
- Credentials không commit.
- Failed job có thể kiểm tra.

---

# 4. Thứ tự triển khai rút gọn

Nếu thời gian hạn chế:

1. WYSIWYG product description.
2. Wishlist.
3. Customer cancel pending order.
4. Low-stock alert.
5. Verified purchase review.
6. Revenue report và CSV.
7. Transactional email.

Nếu còn thời gian:

8. Related products.
9. Coupon.
10. Admin audit log.

---

# 5. Gói tính năng dễ ghi điểm nhất

Bộ cân bằng giữa công sức và khả năng trình bày:

- WYSIWYG.
- Wishlist.
- Customer cancel pending order.
- Low-stock dashboard.
- Verified purchase review.
- Revenue report.
- Transactional email.

Bộ này thể hiện:

- Rich content.
- Dynamic UI.
- Database relationship.
- Authorization.
- Transaction.
- Inventory.
- Moderation.
- Reporting.
- Queue.
- External integration.

---

# 6. Những tính năng chưa nên làm

Không nên bổ sung trong roadmap hiện tại:

- Online payment.
- Multi-vendor.
- Multiple warehouse.
- Real-time shipping tracking.
- Chat.
- AI recommendation.
- WebSocket.
- Mobile app.
- Microservices.
- Elasticsearch.
- Event sourcing.

Các tính năng này làm tăng đáng kể scope, cấu hình, test và rủi ro demo.
