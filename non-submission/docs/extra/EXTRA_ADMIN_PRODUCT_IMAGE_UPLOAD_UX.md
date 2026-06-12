# EXTRA FEATURE SPEC — ADMIN PRODUCT IMAGE UPLOAD UX

## 1. Mục tiêu

Tính năng này cải thiện trải nghiệm upload và quản lý hình ảnh sản phẩm trong trang admin khi tạo hoặc chỉnh sửa sản phẩm.

Mục tiêu chính:

- Admin nhìn thấy khu vực upload rõ ràng.
- Có button upload dễ hiểu.
- Có preview ảnh trước khi lưu.
- Có trạng thái loading và validation error.
- Dễ chọn ảnh chính.
- Dễ xóa ảnh.
- Dễ sắp xếp ảnh.
- Dễ hiểu giới hạn file.
- Dùng Tailwind CSS và jQuery.
- Không thêm thư viện upload hoặc drag-sort nếu chưa được human xác nhận.
- Giữ backend validation nghiêm ngặt.

---

# 2. Bối cảnh

Trước tính năng này, hệ thống đã có hoặc dự kiến có:

- Admin product create/edit.
- Bảng `product_images`.
- Upload ảnh sản phẩm.
- Product gallery ở customer page.
- Placeholder image.
- Validation ảnh cơ bản.
- Storage public.
- Tailwind CSS.
- jQuery.
- Font Awesome Free.

Tính năng này tập trung vào UI/UX của admin, không thay đổi nguyên tắc lưu ảnh an toàn.

---

# 3. Phạm vi

## 3.1. Bao gồm

- Cải thiện khu vực upload ảnh ở admin product form.
- Button upload rõ ràng.
- Text hướng dẫn file hợp lệ.
- Preview ảnh trước khi upload hoặc trước khi submit.
- Hiển thị ảnh hiện có.
- Chọn ảnh chính.
- Xóa ảnh.
- Cập nhật alt text.
- Cập nhật sort order bằng nút lên/xuống.
- Validation error rõ ràng.
- Empty state khi chưa có ảnh.
- Loading state khi upload.
- Responsive admin layout.

## 3.2. Không bao gồm

Không triển khai trong phase này:

- Crop ảnh.
- Resize ảnh tự động.
- Image compression package.
- Drag-and-drop sorting bằng thư viện ngoài.
- Multi-file upload nâng cao kiểu dashboard media.
- CDN.
- S3.
- Direct-to-cloud upload.
- Upload video.
- Upload SVG từ admin.
- Gallery variant-specific nếu schema chưa hỗ trợ.

---

# 4. Nguyên tắc bảo mật

## 4.1. Server validation là bắt buộc

Frontend chỉ hỗ trợ UX.

Server phải kiểm tra:

- File có thật.
- File là image hợp lệ.
- MIME hợp lệ.
- Extension hợp lệ.
- Dung lượng tối đa.
- Không phải file thực thi.
- Không phải SVG trong MVP.
- Product tồn tại.
- Admin có quyền chỉnh sản phẩm.

Định dạng cho phép:

- JPEG.
- PNG.
- WebP.

Không cho phép:

- SVG.
- PHP.
- HTML.
- JavaScript.
- PDF.
- ZIP.

## 4.2. Filename

Không dùng tên file gốc làm tên lưu.

Server tạo filename mới bằng:

- UUID.
- ULID.
- Hash an toàn.

Database chỉ lưu path tương đối.

## 4.3. Storage path

Gợi ý:

    storage/app/public/products/{product_id}/

Database lưu:

    products/{product_id}/image-name.webp

Không lưu full URL.

---

# 5. UI/UX admin đề xuất

## 5.1. Khu vực upload chính

Section nên có:

- Tiêu đề: “Hình ảnh sản phẩm”.
- Mô tả ngắn.
- Button rõ ràng: “Chọn ảnh sản phẩm”.
- Icon upload bằng Font Awesome.
- Helper text:
  - “Hỗ trợ JPG, PNG, WebP.”
  - “Dung lượng tối đa 2MB mỗi ảnh.”
  - “Nên dùng ảnh vuông, nền sáng, tỷ lệ 1:1.”
- Error message.

## 5.2. Upload button

Button nên nổi bật vừa đủ:

Text:

    Chọn ảnh sản phẩm

Icon:

    fa-upload
    fa-image
    fa-plus

Không chỉ dùng icon mà không có text.

Button click mở file input hidden.

## 5.3. Dropzone nhẹ

Có thể tạo dropzone bằng HTML, Tailwind và jQuery.

Yêu cầu:

- Click để chọn file.
- Drag file vào vùng upload.
- Hover state.
- Drag over state.
- Không cần thư viện.
- Nếu drag-drop gây phức tạp, chỉ dùng button là đủ.

Dropzone text:

    Kéo thả ảnh vào đây hoặc bấm để chọn ảnh

## 5.4. Preview ảnh mới

Sau khi chọn file:

- Hiển thị thumbnail preview.
- Hiển thị tên file.
- Hiển thị dung lượng file.
- Có nút xóa khỏi danh sách trước khi submit.
- Báo lỗi sớm nếu file quá lớn hoặc sai type.
- Vẫn validate lại ở server.

Không upload ngay nếu flow hiện tại là submit chung product form.

Nếu flow hiện tại là upload AJAX riêng:

- Hiển thị progress/loading.
- Sau khi thành công, thêm ảnh vào list hiện có.

## 5.5. Danh sách ảnh hiện có

Hiển thị dạng grid:

- Thumbnail.
- Badge “Ảnh chính”.
- Alt text input.
- Sort order.
- Button đặt làm ảnh chính.
- Button di chuyển lên.
- Button di chuyển xuống.
- Button xóa.

Gợi ý action buttons:

- “Đặt làm ảnh chính”.
- “Lên”.
- “Xuống”.
- “Xóa”.

Có icon hỗ trợ nhưng vẫn nên có text trên desktop.

## 5.6. Empty state

Khi chưa có ảnh:

    Chưa có hình ảnh cho sản phẩm này.
    Hãy thêm ít nhất một ảnh để sản phẩm hiển thị đẹp hơn ở trang khách hàng.

Có button:

    Chọn ảnh sản phẩm

## 5.7. Primary image

Quy tắc:

- Mỗi product chỉ có một ảnh chính.
- Nếu upload ảnh đầu tiên, có thể tự đặt làm ảnh chính.
- Nếu xóa ảnh chính, hệ thống chọn ảnh đầu tiên còn lại hoặc yêu cầu admin chọn lại.
- Customer product card dùng ảnh chính.

## 5.8. Sort order

Không cần drag-sort library.

MVP dùng:

- Button lên.
- Button xuống.
- Hoặc input sort order.

Khuyến nghị:

- Button lên/xuống dễ demo và ít bug.
- Sau này mới đề xuất SortableJS nếu thật sự cần drag-and-drop.

---

# 6. jQuery behavior

## 6.1. File đề xuất

    resources/js/admin/product-images.js

Không viết inline JavaScript.

## 6.2. Data attributes

Blade markup nên dùng:

    data-image-upload
    data-image-input
    data-image-dropzone
    data-image-preview-list
    data-image-preview-item
    data-image-remove-preview
    data-existing-image
    data-set-primary-image
    data-delete-image
    data-move-image-up
    data-move-image-down
    data-image-error

## 6.3. File input

File input có thể hidden:

    type="file"
    accept="image/jpeg,image/png,image/webp"
    multiple

Button trigger bằng jQuery.

## 6.4. Client-side validation

Trước khi preview:

- Check MIME type.
- Check extension nếu cần.
- Check size.
- Check count limit nếu có.
- Show error nếu sai.

Nhưng server vẫn validate lại.

## 6.5. Preview

Dùng `URL.createObjectURL(file)` để preview.

Khi remove preview:

- Revoke object URL nếu có thể.
- Remove item khỏi list.
- Update file input hoặc rebuild selected file state.

Nếu việc remove từng file khỏi `FileList` quá phức tạp:

- Có thể yêu cầu admin chọn lại.
- Hoặc dùng DataTransfer nếu browser support phù hợp.

Giữ scope đơn giản và ổn định.

## 6.6. AJAX hay submit chung

Có hai phương án.

### Phương án A: Submit chung product form

Ưu điểm:

- Đơn giản.
- Ít route.
- Dễ test.

Nhược điểm:

- Upload lỗi làm product form fail chung.
- Không có progress chi tiết.

Phù hợp nếu hiện tại product form đã submit tất cả cùng lúc.

### Phương án B: AJAX upload riêng

Ưu điểm:

- UX tốt.
- Có thể upload nhiều ảnh sau khi product đã tồn tại.
- Không làm mất form data khi upload lỗi.

Nhược điểm:

- Cần routes riêng.
- Cần xử lý CSRF, loading, error.
- Test nhiều hơn.

Khuyến nghị:

- Product create: submit chung hoặc tạo product trước rồi redirect edit để upload ảnh.
- Product edit: AJAX upload riêng là UX tốt hơn.

## 6.7. Loading state

Khi upload:

- Disable upload button.
- Hiển thị spinner.
- Hiển thị text “Đang tải ảnh...”.
- Không disable toàn bộ product form nếu chỉ upload ảnh.
- Khi lỗi, restore button.

## 6.8. Delete image

Khi xóa:

- Confirm rõ ràng.
- Có loading ở image card.
- Chỉ remove khỏi DOM sau khi server xác nhận.
- Nếu ảnh chính bị xóa, update badge ảnh chính mới nếu server trả về.

Text:

    Bạn có chắc muốn xóa ảnh này không?

## 6.9. Set primary

Khi đặt ảnh chính:

- Gửi request.
- Server đảm bảo chỉ một ảnh chính.
- UI cập nhật badge.
- Không tự set primary chỉ ở frontend.

## 6.10. Move up/down

Khi bấm lên/xuống:

- Có thể submit PATCH sort order.
- Server trả danh sách thứ tự mới.
- UI reorder theo response.
- Không chỉ reorder DOM mà không lưu server.

---

# 7. Backend contract

## 7.1. Routes đề xuất

Nếu chưa có, có thể dùng:

    POST   /admin/products/{product}/images
    PATCH  /admin/product-images/{image}
    DELETE /admin/product-images/{image}
    PATCH  /admin/product-images/{image}/primary
    PATCH  /admin/products/{product}/images/sort

## 7.2. Upload request

Field:

    images[]

Validation:

- required.
- array.
- mỗi file là image.
- MIME in jpeg,png,webp.
- max size.
- không SVG.

## 7.3. Update image request

Cho phép:

- `alt_text`
- `sort_order`
- `is_primary`, nếu route phù hợp.

Không cho update:

- `path` trực tiếp từ client.
- `product_id` trực tiếp nếu không cần.
- full URL.

## 7.4. JSON success response

    {
        "success": true,
        "message": "Đã tải ảnh sản phẩm.",
        "data": {
            "images": [
                {
                    "id": 10,
                    "url": "/storage/products/1/image.webp",
                    "alt_text": "iPhone 16 màu xanh",
                    "sort_order": 1,
                    "is_primary": true
                }
            ]
        }
    }

## 7.5. JSON validation error

HTTP 422:

    {
        "message": "Dữ liệu không hợp lệ.",
        "errors": {
            "images.0": [
                "Ảnh phải có định dạng JPG, PNG hoặc WebP."
            ]
        }
    }

---

# 8. Tailwind UI guidance

## 8.1. Upload card

Gợi ý style:

- Border dashed.
- Rounded corners.
- Background gray-50.
- Hover border blue.
- Icon upload lớn vừa phải.
- Button rõ ràng.

Classes gợi ý:

    rounded-2xl
    border-2
    border-dashed
    border-gray-300
    bg-gray-50
    p-6
    text-center
    hover:border-blue-400
    hover:bg-blue-50

## 8.2. Image grid

Responsive:

- Mobile: 2 columns.
- Tablet: 3 columns.
- Desktop: 4 columns.

Classes gợi ý:

    grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4

## 8.3. Image card

Card gồm:

- Thumbnail.
- Badge.
- Actions.
- Alt input.

Classes:

    rounded-xl
    border
    bg-white
    p-3
    shadow-sm

## 8.4. Action buttons

Nên rõ ràng:

- Primary action: xanh.
- Dangerous action: đỏ.
- Secondary action: xám.

Không dùng icon-only button nếu action nguy hiểm.

Nếu icon-only:

- Bắt buộc có `aria-label`.
- Có tooltip hoặc title.

---

# 9. Accessibility

- Upload button có text.
- File input có label.
- Dropzone có keyboard fallback.
- Delete button có confirm.
- Image thumbnail có alt.
- Error message gần field.
- Focus ring rõ.
- Không chỉ dùng màu để thể hiện ảnh chính.
- Badge ảnh chính có text.

---

# 10. Test plan

## 10.1. Feature tests

- Admin upload ảnh hợp lệ.
- Customer không upload được.
- File sai MIME bị từ chối.
- File quá lớn bị từ chối.
- SVG bị từ chối.
- Path lưu tương đối.
- Ảnh đầu tiên được set primary nếu chưa có ảnh.
- Set primary chỉ còn một ảnh chính.
- Delete image thành công.
- Delete primary image xử lý đúng.
- Update alt text.
- Update sort order.

## 10.2. Manual browser tests

- Button chọn ảnh rõ ràng.
- Preview ảnh xuất hiện.
- Remove preview hoạt động.
- Upload nhiều ảnh.
- Validation lỗi hiển thị dễ hiểu.
- Mobile admin vẫn dùng được.
- Xóa ảnh có confirm.
- Set primary cập nhật UI.
- Move up/down cập nhật thứ tự.
- Product detail dùng ảnh mới.

## 10.3. Security tests

- Upload file `.php` đổi đuôi bị từ chối.
- Upload SVG bị từ chối.
- Alt text có script không thực thi.
- User thường không truy cập routes admin.
- Full URL không được lưu vào database.

---

# 11. Acceptance criteria

Tính năng hoàn thành khi:

- Admin edit product có khu vực upload rõ ràng.
- Có button “Chọn ảnh sản phẩm”.
- Có helper text định dạng và dung lượng.
- Có preview ảnh mới.
- Có danh sách ảnh hiện có.
- Có thể đặt ảnh chính.
- Có thể xóa ảnh.
- Có thể cập nhật alt text.
- Có thể sắp xếp ảnh bằng cách đơn giản.
- Validation lỗi hiển thị rõ.
- Loading state hoạt động.
- Không tự cài thư viện upload.
- Không tự cài thư viện drag-sort.
- Không cho upload SVG.
- Không lưu full URL.
- Không dùng filename gốc.
- Có feature tests chính.

---

# 12. Prompt cho Cursor

Dùng prompt này để implement.

    Read:
    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/IMAGE_STRATEGY.md
    @docs/UI_GUIDELINES.md
    @docs/EXTRA_ADMIN_PRODUCT_IMAGE_UPLOAD_UX.md

    Implement the Admin Product Image Upload UX improvement.

    Scope:
    - Improve image upload UI on admin product create/edit or edit page according to existing project flow.
    - Use Tailwind CSS and jQuery only.
    - Add a clear upload button.
    - Add helper text for JPG, PNG, WebP and max file size.
    - Add image preview before submit or before upload.
    - Show existing images in a responsive grid.
    - Allow setting primary image.
    - Allow deleting image with confirmation.
    - Allow updating alt text.
    - Allow simple sort order using up/down buttons or existing sort field.
    - Keep server-side validation strict.
    - Do not allow SVG upload.
    - Do not store full URLs.
    - Do not add upload libraries.
    - Do not add drag-sort libraries.
    - Do not modify customer product gallery except if required to reflect existing image data.
    - Add feature tests for upload validation and image management.

    Before editing:
    1. Inspect current admin product create/edit views.
    2. Inspect ProductImage model and routes.
    3. Inspect current upload validation.
    4. Report files to modify.
    5. Then implement the smallest complete version.

    After implementation:
    - Run focused tests.
    - Run php artisan test.
    - Run npm run build.
    - Report changed files and remaining limitations.

---

# 13. Phạm vi mở rộng sau này

Sau khi ổn định, có thể bổ sung:

- Drag-and-drop sorting bằng SortableJS nếu human duyệt.
- Image crop.
- Client-side compression.
- Separate media library.
- S3 storage.
- CDN.
- Variant-specific images.
- Bulk delete.
- Undo delete.

Không triển khai trong phase hiện tại.
