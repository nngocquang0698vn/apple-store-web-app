# EXTRA FEATURE SPEC — PRODUCT DETAIL GALLERY AND DESCRIPTION UX

## 1. Mục tiêu

Tính năng này cải thiện trang chi tiết sản phẩm sau khi hệ thống đã có WYSIWYG product description.

Mục tiêu chính:

- Trang product detail nhìn thân thiện và chuyên nghiệp hơn.
- Mô tả sản phẩm được căn chỉnh đẹp bằng Tailwind CSS.
- Nội dung WYSIWYG hiển thị rõ ràng, dễ đọc, không bị vỡ layout.
- Product gallery có carousel ảnh sản phẩm.
- Gallery responsive tốt trên mobile, tablet và desktop.
- Vẫn dùng Laravel Blade SSR và jQuery.
- Không mặc định thêm thư viện carousel mới.
- Nếu Cursor thấy cần thư viện carousel, chỉ được đề xuất và chờ human xác nhận.

---

# 2. Bối cảnh

Trước tính năng này, hệ thống đã có:

- Product detail page.
- Product images.
- Product variants.
- Product description đã được chỉnh bằng WYSIWYG.
- Description đã được sanitize trước khi render.
- Tailwind CSS.
- jQuery.
- Font Awesome Free.

Tính năng này tập trung vào UI/UX, không thay đổi nghiệp vụ checkout, cart hoặc pricing.

---

# 3. Phạm vi

## 3.1. Bao gồm

- Cải thiện layout product detail.
- Cải thiện vùng product gallery.
- Thêm carousel ảnh bằng jQuery.
- Thêm thumbnail navigation.
- Thêm prev/next button.
- Thêm keyboard navigation cơ bản.
- Cải thiện vùng product description đã render từ WYSIWYG.
- Tailwind classes hợp lý cho heading, paragraph, list, link và spacing.
- Responsive cho mobile, tablet và desktop.
- Empty/fallback state khi sản phẩm chưa có ảnh.
- Accessibility cơ bản.

## 3.2. Không bao gồm

Không triển khai trong phase này:

- Upload ảnh admin.
- Crop ảnh.
- Zoom ảnh nâng cao.
- Lightbox phức tạp.
- Pinch-to-zoom.
- Video gallery.
- 360-degree product view.
- Review.
- Related products.
- Cart logic.
- Thay đổi database.
- Thay đổi sanitize policy của WYSIWYG nếu đã ổn định.

---

# 4. Nguyên tắc thiết kế

## 4.1. SSR first

Trang product detail phải render tốt ngay từ server.

Nếu JavaScript tắt:

- Ảnh chính vẫn hiển thị.
- Thumbnail vẫn là link hoặc button đơn giản.
- Description vẫn đọc được.
- Add-to-cart vẫn hoạt động theo form fallback.

jQuery chỉ nâng cấp trải nghiệm carousel.

## 4.2. Không thêm thư viện mặc định

MVP của feature này dùng jQuery tự viết.

Cursor không được tự cài:

- Swiper.
- Slick.
- Owl Carousel.
- Glide.
- Splide.
- Lightbox package.

Nếu thấy cần, Cursor phải tạo proposal và chờ human xác nhận.

## 4.3. Khi nào mới nên đề xuất thư viện carousel

Chỉ đề xuất thư viện nếu cần nhiều tính năng như:

- Swipe mượt đa trình duyệt.
- Infinite loop.
- Lazy loading nâng cao.
- Zoom.
- Nested carousel.
- Accessibility hoàn chỉnh khó tự làm.
- Gallery có rất nhiều ảnh.

Nếu chỉ cần main image, thumbnail, prev/next và responsive, dùng jQuery tự viết là đủ.

---

# 5. Layout product detail đề xuất

## 5.1. Desktop layout

Desktop từ `lg` trở lên:

- Cột trái: product gallery.
- Cột phải: product information và add-to-cart.
- Description nằm bên dưới hoặc trong section riêng full width.

Gợi ý layout:

    product detail container
    ├── left: gallery, width 50%
    └── right: product summary, width 50%

    below:
    ├── description
    └── specifications

## 5.2. Mobile layout

Mobile:

1. Gallery.
2. Product name.
3. Price.
4. Variant selectors.
5. Quantity và add-to-cart.
6. Short description.
7. Description.
8. Specifications.

Không đặt gallery và summary hai cột trên mobile.

## 5.3. Tablet layout

Tablet có thể:

- Gallery trên.
- Summary dưới.
- Hoặc hai cột nếu đủ rộng.

Ưu tiên dễ đọc hơn là ép layout giống desktop.

---

# 6. Product gallery carousel

## 6.1. Thành phần UI

Gallery gồm:

- Main image.
- Thumbnail list.
- Previous button.
- Next button.
- Current image indicator.
- Fallback placeholder.
- Optional badge nếu ảnh thuộc variant hiện tại.

## 6.2. Main image

Yêu cầu:

- Container vuông hoặc gần vuông.
- Nền trung tính.
- `object-contain`.
- Không cắt mất sản phẩm.
- Có alt text.
- Có stable dimensions để tránh layout shift.
- Ảnh đầu tiên không cần lazy load nếu nằm trên fold.

Gợi ý Tailwind:

    aspect-square
    rounded-2xl
    bg-gray-50
    border
    object-contain

## 6.3. Thumbnail list

Yêu cầu:

- Hiển thị dưới main image.
- Horizontal scroll trên mobile.
- Grid hoặc flex trên desktop.
- Thumbnail active có border hoặc ring.
- Thumbnail dùng button thay vì div.
- Có `aria-label`.
- Không quá nhỏ trên mobile.

Gợi ý:

    flex gap-3 overflow-x-auto
    w-20 h-20
    rounded-xl
    ring-2 ring-blue-500

## 6.4. Prev/next button

Yêu cầu:

- Có Font Awesome icon.
- Button đủ lớn để tap trên mobile.
- Có `aria-label`.
- Ẩn hoặc disable nếu chỉ có một ảnh.
- Không che mất sản phẩm.

Icon gợi ý:

- `fa-chevron-left`
- `fa-chevron-right`

## 6.5. Keyboard support

Khi gallery đang focus:

- Arrow left: ảnh trước.
- Arrow right: ảnh sau.
- Home: ảnh đầu.
- End: ảnh cuối.

Không bắt buộc nếu scope thời gian ngắn, nhưng có thể ghi điểm accessibility.

## 6.6. Variant-aware image

Nếu product variant có ảnh riêng hoặc mapping ảnh theo color:

- Khi user chọn màu, gallery tự chuyển sang ảnh phù hợp.
- Nếu không có ảnh theo variant, giữ ảnh hiện tại.
- Không cần thay đổi schema nếu chưa có mapping.

Nếu schema hiện tại chỉ có product images chung:

- Chỉ làm carousel chung.
- Không tự thêm variant-image schema trong feature này.

---

# 7. jQuery behavior

## 7.1. File đề xuất

    resources/js/product-gallery.js

Nếu project đang gom JS:

    resources/js/product-detail.js

Không viết inline JavaScript.

## 7.2. Data attributes

Blade nên dùng data attributes:

    data-product-gallery
    data-gallery-main-image
    data-gallery-thumbnail
    data-gallery-prev
    data-gallery-next
    data-gallery-current-index
    data-gallery-total
    data-gallery-image-src
    data-gallery-image-alt

## 7.3. State

jQuery quản lý:

- Current index.
- Image list.
- Active thumbnail.
- Disabled state cho prev/next.
- Alt text.
- Optional loading state.

Không cần lưu state vào local storage.

## 7.4. Behavior

Khi click thumbnail:

1. Lấy ảnh từ data attribute.
2. Cập nhật main image.
3. Cập nhật alt.
4. Cập nhật active thumbnail.
5. Cập nhật indicator.

Khi click next:

- Nếu chưa cuối, tăng index.
- Nếu cuối, có thể quay về đầu hoặc disable.
- Khuyến nghị disable ở đầu/cuối để đơn giản.

Khi ảnh lỗi:

- Fallback về placeholder.
- Hiển thị thông báo nhẹ nếu cần.

## 7.5. Touch swipe

Không bắt buộc.

Nếu làm, chỉ cần hỗ trợ swipe cơ bản:

- Touch start.
- Touch end.
- Nếu khoảng cách ngang đủ lớn, chuyển ảnh.

Nếu logic swipe làm code phức tạp, bỏ qua để giữ scope.

---

# 8. Product description UX

## 8.1. Vấn đề thường gặp sau WYSIWYG

Sau khi dùng WYSIWYG, description dễ gặp:

- Heading quá to hoặc quá sát nhau.
- Paragraph thiếu khoảng cách.
- List bị lệch.
- Link không nổi bật.
- Ảnh hoặc nội dung dài làm vỡ layout.
- HTML render nhìn không giống editor.
- Mobile spacing quá dày hoặc quá chật.

## 8.2. Container description

Gợi ý section:

    rounded-2xl
    border
    bg-white
    p-5
    md:p-8
    shadow-sm

Nội dung nên có max width để dễ đọc:

    max-w-none
    leading-7
    text-gray-700

## 8.3. Tailwind typography

Nếu `@tailwindcss/typography` đã được cài:

- Dùng `prose`.
- Dùng `prose-gray`.
- Dùng `max-w-none`.
- Tùy chỉnh `prose-h2`, `prose-p`, `prose-a`, `prose-li`.

Nếu chưa cài:

- Không tự cài plugin.
- Dùng CSS class cục bộ cho rich content.
- Hoặc Cursor tạo proposal để human duyệt plugin.

## 8.4. Rich content CSS không cần plugin

Có thể tạo class:

    product-description

Trong CSS:

- Style h2.
- Style h3.
- Style p.
- Style ul.
- Style ol.
- Style li.
- Style blockquote.
- Style a.
- Style table nếu có.
- Style img nếu WYSIWYG cho phép ảnh.

Không dùng CSS quá rộng ảnh hưởng toàn site.

## 8.5. Heading

H2:

- Font semibold.
- Text gray-900.
- Margin top rõ.
- Margin bottom vừa phải.

H3:

- Nhỏ hơn H2.
- Dùng cho subsection.

Không để heading đầu tiên có margin top quá lớn.

## 8.6. Paragraph và list

Paragraph:

- Dòng dễ đọc.
- Line-height thoải mái.
- Khoảng cách giữa đoạn vừa phải.

List:

- Có bullet hoặc decimal rõ.
- Indent hợp lý.
- Khoảng cách giữa item vừa phải.

## 8.7. Link

Link trong description:

- Màu xanh.
- Underline khi hover.
- Không mở `javascript:` URL.
- External link có `rel="noopener noreferrer"` nếu mở tab mới.

## 8.8. Long content

Nếu description rất dài:

- Có thể chia section.
- Optional “Xem thêm” collapse.
- Không bắt buộc trong phase đầu.

Nếu làm “Xem thêm”:

- SSR vẫn hiển thị đầy đủ hoặc có fallback.
- jQuery chỉ collapse trên client.
- Không che mất thông tin quan trọng.

---

# 9. Specifications section

Nếu đã có field `specifications`:

- Hiển thị riêng với description.
- Dùng table hoặc definition list.
- Không trộn toàn bộ thông số vào description.

Gợi ý UI:

- Section “Thông số kỹ thuật”.
- Rows rõ ràng.
- Mobile chuyển thành stacked rows.

Không cần thay đổi database trong feature này.

---

# 10. Empty and fallback states

## 10.1. Không có ảnh

Hiển thị:

- Placeholder SVG.
- Không hiện prev/next.
- Không hiện thumbnail list.
- Alt text phù hợp.

## 10.2. Chỉ có một ảnh

Hiển thị:

- Main image.
- Có thể ẩn thumbnail list.
- Ẩn hoặc disable prev/next.

## 10.3. Không có description

Hiển thị section ngắn:

    Thông tin chi tiết sản phẩm đang được cập nhật.

Không để vùng trắng lớn.

---

# 11. Accessibility

Yêu cầu:

- Main image có alt text.
- Thumbnail là button có aria-label.
- Prev/next có aria-label.
- Keyboard focus rõ.
- Active thumbnail có `aria-current`.
- Indicator có text screen-reader nếu cần.
- Button không chỉ dựa vào icon.
- Description link có trạng thái hover/focus.

---

# 12. Performance

- Không tải ảnh quá lớn nếu đã có thumbnail.
- Ảnh dưới fold dùng lazy loading.
- Main image đầu tiên ưu tiên hiển thị.
- Không query images trong Blade loop.
- Product detail eager load images.
- Không tạo nhiều event handler trùng khi page reload partial.

---

# 13. Test plan

## 13.1. Feature hoặc view tests

- Product detail render khi có nhiều ảnh.
- Product detail render khi không có ảnh.
- Product detail render khi có một ảnh.
- Description đã sanitize được render.
- Script trong description không xuất hiện.
- Placeholder xuất hiện đúng.
- Alt text có mặt.

## 13.2. Manual browser tests

- Desktop layout hai cột hợp lý.
- Mobile layout một cột.
- Thumbnail click đổi ảnh.
- Prev/next hoạt động.
- Ảnh active có style rõ.
- Description dễ đọc trên mobile.
- Long description không vỡ layout.
- JavaScript tắt vẫn xem được ảnh chính và description.
- Add-to-cart không bị ảnh hưởng.

## 13.3. Accessibility manual tests

- Tab được đến gallery controls.
- Enter hoặc Space dùng được thumbnail.
- Screen reader có label cho nút.
- Focus ring thấy rõ.

---

# 14. Acceptance criteria

Tính năng hoàn thành khi:

- Product detail có layout responsive tốt hơn.
- Gallery có main image và thumbnails.
- Carousel hoạt động bằng jQuery.
- Không dùng thư viện carousel mới.
- Prev/next hoạt động.
- Thumbnail active rõ ràng.
- Fallback placeholder đúng.
- Description WYSIWYG hiển thị đẹp bằng Tailwind hoặc CSS cục bộ.
- Description không vỡ layout trên mobile.
- Nội dung HTML nguy hiểm không render.
- Không ảnh hưởng add-to-cart.
- Không thay đổi database.
- Có test hoặc checklist manual rõ ràng.

---

# 15. Prompt cho Cursor

Dùng prompt này để implement.

    Read:
    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/UI_GUIDELINES.md
    @docs/IMAGE_STRATEGY.md
    @docs/EXTRA_PRODUCT_DETAIL_GALLERY_DESCRIPTION_UX.md

    Implement the Product Detail Gallery and Description UX improvement.

    Scope:
    - Improve product detail layout with Tailwind CSS.
    - Keep Laravel Blade SSR.
    - Use jQuery only.
    - Add a product image gallery carousel with main image, thumbnails, prev and next buttons.
    - Keep the page usable without JavaScript.
    - Improve WYSIWYG description rendering with responsive Tailwind or local CSS classes.
    - Do not install a carousel library.
    - Do not install @tailwindcss/typography unless you create a proposal and human approves.
    - Do not change database schema.
    - Do not modify cart or checkout logic.
    - Do not weaken HTML sanitization.
    - Add or update tests where appropriate.

    Before editing:
    1. Inspect the current product detail Blade.
    2. Inspect product image relationships.
    3. Inspect current WYSIWYG description rendering.
    4. Report files to modify.
    5. Then implement the smallest complete version.

    After implementation:
    - Run focused tests.
    - Run php artisan test.
    - Run npm run build.
    - Report changed files and remaining limitations.
