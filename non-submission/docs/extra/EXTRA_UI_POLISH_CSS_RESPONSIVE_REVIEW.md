# EXTRA FEATURE SPEC — UI POLISH, CSS AUDIT, RESPONSIVE REVIEW AND SCREENSHOT-BASED LAYOUT FIX

## 1. Mục tiêu

Tài liệu này dùng để rà soát và cải thiện toàn bộ giao diện website sau khi các chức năng chính đã hoàn thành.

Mục tiêu chính:

- Rà soát lại CSS, Tailwind class và layout toàn hệ thống.
- Phát hiện và sửa các lỗi responsive trên mobile, tablet và desktop.
- Cải thiện các layout hiện đang có vấn đề dựa trên hình ảnh screenshot do người dùng đính kèm.
- Làm giao diện khách hàng thân thiện hơn, dễ mua hàng hơn.
- Làm giao diện quản trị rõ ràng và dễ thao tác hơn.
- Thêm các hiệu ứng nhỏ như hover, transition, focus, loading state.
- Thêm hiệu ứng khi hover product card, ví dụ ảnh sản phẩm phóng to nhẹ.
- Không thay đổi nghiệp vụ.
- Không thay đổi database.
- Không cài thêm UI framework hoặc animation library nếu chưa được human xác nhận.

---

# 2. Bối cảnh

Hệ thống đã hoàn thành các chức năng chính:

- Trang chủ.
- Danh sách sản phẩm.
- Tìm kiếm, lọc và sắp xếp.
- Trang chi tiết sản phẩm.
- Product gallery carousel.
- WYSIWYG product description.
- Giỏ hàng động bằng jQuery.
- Checkout.
- Admin product management.
- Admin image upload UX.

Tuy nhiên, sau khi có đủ chức năng, giao diện có thể phát sinh một số vấn đề:

- Layout chưa cân đối.
- Card sản phẩm chưa đẹp.
- Khoảng cách giữa các phần chưa đều.
- Mobile bị tràn hoặc quá chật.
- Product detail chưa thuận mắt.
- Admin form quá dài hoặc khó thao tác.
- Button không nổi bật.
- Hover/focus chưa rõ.
- Một số thành phần thiếu transition khiến website chưa mượt.

Phase này tập trung vào việc polish UI/UX sau khi nghiệp vụ đã ổn định.

---

# 3. Phạm vi rà soát

## 3.1. Khu vực khách hàng

Rà soát:

- Header.
- Mobile navigation.
- Search bar.
- Category navigation.
- Trang chủ.
- Product grid.
- Product card.
- Product listing filters.
- Product detail page.
- Product gallery carousel.
- Product description sau WYSIWYG.
- Add-to-cart area.
- Cart page.
- Checkout page.
- Account page.
- Order history page.
- Empty states.
- Flash messages.
- Error states.

## 3.2. Khu vực quản trị

Rà soát:

- Admin dashboard.
- Admin sidebar.
- Admin header.
- Product list table.
- Product create/edit form.
- Variant management.
- Image upload area.
- Existing image grid.
- Order list.
- Order detail.
- Form validation error.
- Admin action buttons.

## 3.3. Không bao gồm

Không làm trong phase này:

- Thay đổi nghiệp vụ.
- Thay đổi database schema.
- Thay đổi route.
- Viết lại toàn bộ UI từ đầu.
- Thay đổi authentication.
- Thay đổi cart logic.
- Thay đổi checkout logic.
- Thêm React, Vue, Alpine, Livewire hoặc Inertia.
- Thêm animation library.
- Thêm component library.
- Thêm Tailwind plugin nếu chưa được human xác nhận.

---

# 4. Screenshot-based layout review

## 4.1. Mục tiêu

Vì layout hiện có có thể đang bị lỗi, Cursor cần ưu tiên phân tích screenshot do người dùng đính kèm trước khi sửa.

Không được sửa CSS theo cảm tính.

Cursor phải:

1. Xem từng screenshot.
2. Xác định trang tương ứng.
3. Mô tả lỗi layout nhìn thấy.
4. Phân loại mức độ nghiêm trọng.
5. Tìm file Blade/CSS/JS liên quan.
6. Đề xuất hướng sửa nhỏ nhất.
7. Sau đó mới chỉnh code.

## 4.2. Cách đặt tên screenshot gợi ý

Người dùng nên đính kèm screenshot với tên dễ hiểu nếu có thể:

    home-desktop.png
    home-mobile.png
    product-list-desktop.png
    product-list-mobile.png
    product-detail-desktop.png
    product-detail-mobile.png
    cart-mobile.png
    checkout-mobile.png
    admin-product-edit.png
    admin-image-upload.png

Nếu không có tên file rõ, Cursor vẫn phải dựa vào nội dung ảnh để suy đoán trang.

## 4.3. Những lỗi cần tìm trong screenshot

### Layout

- Thành phần bị lệch.
- Thành phần bị tràn khỏi màn hình.
- Khoảng trắng quá nhiều.
- Khoảng cách quá chật.
- Card không đều chiều cao.
- Grid bị vỡ.
- Header che nội dung.
- Footer không đúng vị trí.
- Sidebar admin gây tràn.
- Modal hoặc dropdown bị cắt.

### Typography

- Chữ quá nhỏ.
- Chữ quá lớn.
- Line-height khó đọc.
- Heading không phân cấp rõ.
- Text dài làm vỡ card.
- Màu chữ thiếu tương phản.

### Product card

- Ảnh méo hoặc bị cắt sai.
- Giá không nổi bật.
- Button không rõ.
- Tên sản phẩm quá dài làm lệch card.
- Hover gây nhảy layout.
- Badge che ảnh hoặc che text.

### Product detail

- Gallery chiếm quá nhiều chỗ.
- Thumbnail quá nhỏ.
- Prev/next button khó bấm.
- Phần giá và add-to-cart chưa nổi bật.
- Description WYSIWYG bị rối.
- Mobile phải cuộn quá nhiều trước khi thấy nút mua.

### Cart và checkout

- Bảng cart bị tràn ngang trên mobile.
- Quantity stepper quá nhỏ.
- Summary khó nhìn.
- Nút checkout không nổi bật.
- Error message xa input.
- Tổng tiền không dễ nhận biết.

### Admin

- Form quá rộng hoặc quá hẹp.
- Label và input không thẳng hàng.
- Button upload không rõ.
- Preview ảnh quá nhỏ hoặc quá lớn.
- Table tràn màn hình.
- Action button quá sát nhau.
- Error message khó thấy.

## 4.4. Mẫu báo cáo trước khi sửa

Cursor phải báo cáo theo mẫu:

    Screenshot review:

    1. Trang: Product listing desktop
       Vấn đề:
       - Product card không đều chiều cao.
       - Ảnh sản phẩm quá sát mép card.
       - Giá chưa nổi bật.
       Mức độ: Medium
       File nghi ngờ:
       - resources/views/products/index.blade.php
       - resources/views/components/product-card.blade.php
       Hướng sửa:
       - Chuẩn hóa chiều cao vùng ảnh.
       - Thêm line-clamp cho tên sản phẩm.
       - Thêm hover shadow và image scale nhẹ.

    2. Trang: Cart mobile
       Vấn đề:
       - Table tràn ngang.
       Mức độ: High
       File nghi ngờ:
       - resources/views/cart/index.blade.php
       Hướng sửa:
       - Chuyển cart item thành card layout trên mobile.
       - Giữ table layout từ md trở lên.

Sau đó mới sửa file.

---

# 5. Nguyên tắc thiết kế

## 5.1. Giao diện phục vụ chức năng

Hiệu ứng chỉ nên làm giao diện dễ hiểu hơn, không làm người dùng mất tập trung.

Nên làm:

- Hover product card nhẹ.
- Ảnh sản phẩm scale nhẹ khi hover.
- Button có hover và focus state.
- Input có focus ring.
- Card có shadow nhẹ khi hover.
- Loading state khi AJAX đang chạy.
- Empty state có lời hướng dẫn.

Không nên làm:

- Animation quá mạnh.
- Card nhảy layout khi hover.
- Text rung hoặc di chuyển.
- Hover làm mất khả năng đọc.
- Dùng quá nhiều màu nhấn.
- Hiệu ứng khiến website chậm hoặc rối.

## 5.2. Mobile-first

Thiết kế theo hướng mobile trước, sau đó mở rộng lên tablet và desktop.

Breakpoint gợi ý:

- Mobile: mặc định.
- Tablet: `md`.
- Desktop: `lg`.
- Large desktop: `xl`.

Không hard-code width quá lớn gây tràn mobile.

## 5.3. Consistency

Các thành phần giống nhau nên có style giống nhau:

- Button.
- Input.
- Select.
- Product card.
- Admin card.
- Table.
- Badge.
- Modal.
- Flash message.
- Error message.
- Empty state.

Nếu mỗi trang dùng style khác nhau, website sẽ thiếu cảm giác thống nhất.

## 5.4. Accessibility

Cần đảm bảo:

- Focus state rõ ràng.
- Button có text hoặc `aria-label`.
- Icon không thay thế hoàn toàn nội dung quan trọng.
- Màu chữ đủ tương phản.
- Không chỉ dùng màu để báo lỗi.
- Hover effect không phải cách duy nhất để hiểu thông tin.
- Animation không gây khó chịu.

---

# 6. Checklist responsive

## 6.1. Header

Kiểm tra:

- Header không bị tràn ở mobile.
- Logo hoặc tên shop không chiếm quá nhiều chiều ngang.
- Search hiển thị hợp lý.
- Cart icon dễ thấy.
- Account menu dễ thao tác.
- Mobile menu mở/đóng rõ ràng.
- Header không che mất nội dung khi sticky.

Gợi ý:

- Desktop: logo, navigation, search, account, cart trên một hàng.
- Mobile: logo, menu button, cart; search có thể nằm dưới hoặc trong menu.

## 6.2. Product grid

Kiểm tra số cột:

- Mobile: 1 hoặc 2 cột.
- Tablet: 2 hoặc 3 cột.
- Desktop: 3 hoặc 4 cột.
- Large desktop: 4 hoặc 5 cột nếu phù hợp.

Gợi ý Tailwind:

    grid grid-cols-2 gap-4 sm:gap-5 md:grid-cols-3 lg:grid-cols-4

Nếu product card có nhiều thông tin, mobile nên dùng 1 cột hoặc 2 cột nhưng card phải gọn.

## 6.3. Product card

Kiểm tra:

- Ảnh không méo.
- Tên sản phẩm không làm card cao bất thường.
- Giá dễ đọc.
- Button dễ bấm.
- Badge không che nội dung quan trọng.
- Hover không làm layout nhảy.
- Card có trạng thái focus nếu là link.

## 6.4. Product detail

Kiểm tra:

- Mobile hiển thị một cột.
- Desktop hiển thị gallery và thông tin thành hai cột.
- Gallery không bị tràn.
- Thumbnail scroll tốt trên mobile.
- Add-to-cart dễ thao tác.
- Description dễ đọc.
- Heading và list trong description không vỡ layout.

## 6.5. Cart page

Kiểm tra:

- Mobile không dùng table quá rộng nếu gây tràn.
- Item cart có thể chuyển thành card trên mobile.
- Quantity stepper dễ bấm.
- Nút xóa không quá nhỏ.
- Summary rõ ràng.
- Checkout button dễ thấy.

## 6.6. Checkout page

Kiểm tra:

- Form nhận hàng dễ nhập.
- Summary không quá xa form trên mobile.
- Error message gần input liên quan.
- Button đặt hàng rõ ràng.
- Tránh layout hai cột trên mobile nếu chật.

## 6.7. Admin pages

Kiểm tra:

- Sidebar không chiếm quá nhiều màn hình nhỏ.
- Table có horizontal scroll khi cần.
- Form có max width hợp lý.
- Action button không bị dính nhau.
- Upload image grid responsive.
- Dashboard card không bị tràn.

---

# 7. Checklist CSS/Tailwind

## 7.1. Loại bỏ class dư thừa

Rà soát:

- Class Tailwind lặp vô ích.
- Class mâu thuẫn như `p-4 p-6`.
- Width cố định không cần thiết.
- Inline style.
- CSS custom không còn dùng.
- Class quá dài lặp lại nhiều nơi.

Nếu một cụm class lặp nhiều lần, cân nhắc tạo Blade component.

## 7.2. Không lạm dụng custom CSS

Ưu tiên Tailwind.

Chỉ dùng CSS cục bộ khi:

- Style rich text WYSIWYG.
- Cần selector cho nội dung HTML động.
- Cần animation nhỏ.
- Tailwind class quá dài hoặc khó bảo trì.

## 7.3. Chuẩn hóa spacing

Gợi ý:

- Page container: `mx-auto max-w-7xl px-4 sm:px-6 lg:px-8`.
- Section padding mobile: `py-8`.
- Section padding desktop: `lg:py-12`.
- Card padding: `p-4` hoặc `p-5`.
- Form field gap: `space-y-4`.

Không nên mỗi trang dùng container width khác nhau nếu không có lý do.

## 7.4. Chuẩn hóa border radius

Gợi ý:

- Button: `rounded-xl`.
- Card: `rounded-2xl`.
- Input: `rounded-xl`.
- Badge: `rounded-full`.
- Image: `rounded-xl` hoặc `rounded-2xl`.

## 7.5. Chuẩn hóa shadow

Không dùng shadow quá mạnh.

Gợi ý:

- Card thường: `shadow-sm`.
- Card hover: `hover:shadow-md`.
- Modal: `shadow-xl`.
- Không dùng nhiều shadow nested.

## 7.6. Chuẩn hóa transition

Gợi ý:

    transition
    duration-200
    ease-out

Không dùng duration quá dài cho thao tác mua hàng.

---

# 8. Hiệu ứng đề xuất

## 8.1. Product card hover image scale

Mục tiêu:

- Khi hover vào card sản phẩm, ảnh phóng to nhẹ.
- Card có shadow nhẹ.
- Không làm layout nhảy.
- Không làm ảnh tràn ra ngoài container.

Gợi ý markup:

    <article class="group rounded-2xl border bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="aspect-square overflow-hidden rounded-xl bg-gray-50">
            <img
                class="h-full w-full object-contain transition-transform duration-300 ease-out group-hover:scale-105"
                src="..."
                alt="..."
            >
        </div>
    </article>

Điểm quan trọng:

- Container ảnh phải có `overflow-hidden`.
- Scale chỉ áp dụng lên `img`.
- Card hover không làm thay đổi kích thước thật.
- Dùng `group` để hover card ảnh hưởng đến ảnh.

## 8.2. Product card button hover

Gợi ý:

    transition duration-200
    hover:bg-blue-700
    focus-visible:outline
    focus-visible:outline-2
    focus-visible:outline-offset-2
    focus-visible:outline-blue-600

Button nên có trạng thái:

- Default.
- Hover.
- Focus.
- Disabled.
- Loading nếu có AJAX.

## 8.3. Product gallery thumbnail hover

Gợi ý:

- Thumbnail hover sáng hơn.
- Active thumbnail có ring.
- Không scale quá mạnh.

Classes gợi ý:

    transition
    hover:border-blue-400
    hover:bg-blue-50
    aria-current:ring-2
    aria-current:ring-blue-500

## 8.4. Cart item update loading

Khi cập nhật quantity:

- Row có opacity nhẹ.
- Nút tăng giảm disabled.
- Spinner nhỏ.
- Không khóa toàn bộ trang.

Gợi ý:

    opacity-60 pointer-events-none

Chỉ dùng cho item đang pending.

## 8.5. Admin image upload hover

Upload area:

- Border dashed.
- Hover border xanh.
- Hover background xanh nhạt.
- Drag-over state rõ hơn.

Gợi ý:

    border-2 border-dashed border-gray-300 bg-gray-50
    hover:border-blue-400 hover:bg-blue-50
    transition duration-200

## 8.6. Flash message animation

Có thể thêm transition nhẹ:

- Fade in.
- Slide down nhẹ.
- Auto dismiss nếu đã có logic.

Không cần animation phức tạp.

## 8.7. Empty state illustration

Không cần hình phức tạp.

Có thể dùng:

- Icon Font Awesome.
- Text hướng dẫn.
- Button hành động tiếp theo.

Ví dụ:

- Cart rỗng → “Tiếp tục mua sắm”.
- Không tìm thấy sản phẩm → “Xóa bộ lọc”.
- Chưa có ảnh → “Chọn ảnh sản phẩm”.

---

# 9. Motion reduce

Nếu thêm transition/animation, nên hạn chế gây khó chịu.

Gợi ý Tailwind:

    motion-safe:transition
    motion-safe:duration-200
    motion-reduce:transition-none

Ưu tiên hiệu ứng nhẹ, không làm người dùng mất tập trung.

---

# 10. Component cần rà soát kỹ

## 10.1. Product card

Yêu cầu sau polish:

- Card đồng đều.
- Ảnh scale nhẹ khi hover.
- Tên sản phẩm line-clamp hợp lý.
- Giá nổi bật.
- Button rõ ràng.
- Badge không che ảnh.
- Mobile không quá chật.

## 10.2. Product detail gallery

Yêu cầu:

- Main image cân đối.
- Thumbnail rõ ràng.
- Prev/next dễ bấm.
- Mobile không tràn.
- Description nằm dưới thông tin chính hợp lý.

## 10.3. Product description

Yêu cầu:

- Heading rõ.
- Paragraph dễ đọc.
- List có khoảng cách.
- Link nổi bật.
- Không vỡ layout.
- Không để nội dung quá sát mép.

## 10.4. Cart page

Yêu cầu:

- Mobile không tràn table.
- Quantity stepper dễ dùng.
- Tổng tiền dễ thấy.
- Checkout button nổi bật.
- Loading state khi update.

## 10.5. Admin product form

Yêu cầu:

- Form không quá rộng.
- Section chia rõ.
- Button chính nổi bật.
- Error message gần input.
- Upload image area dễ hiểu.

---

# 11. Quy trình implement an toàn

## Bước 1: Audit screenshot

Cursor xem hình đính kèm và ghi nhận lỗi layout.

## Bước 2: Audit code

Cursor kiểm tra:

- Blade file liên quan.
- CSS file.
- JS file nếu có interaction.
- Component đang dùng chung.

## Bước 3: Phân loại lỗi

Phân loại:

- High: tràn layout, không thao tác được, mobile hỏng.
- Medium: nhìn xấu, spacing lệch, card không đều.
- Low: polish nhỏ như hover, transition, shadow.

## Bước 4: Sửa theo từng nhóm nhỏ

Không sửa toàn bộ website trong một lần.

Thứ tự đề xuất:

1. Header và layout container.
2. Product card.
3. Product listing.
4. Product detail.
5. Cart và checkout.
6. Admin product form.
7. Admin image upload.
8. Minor hover/focus/transition.

## Bước 5: Kiểm tra lại

Sau khi sửa:

- Chạy build.
- Kiểm tra mobile.
- Kiểm tra desktop.
- So sánh với screenshot ban đầu.
- Không để regression.

---

# 12. Test và kiểm tra thủ công

## 12.1. Viewport cần test

Kiểm tra tối thiểu:

- 375px mobile.
- 430px large mobile.
- 768px tablet.
- 1024px laptop.
- 1280px desktop.
- 1440px large desktop.

## 12.2. Browser manual checklist

- Header không tràn.
- Product grid đúng số cột.
- Product card hover mượt.
- Product image scale không vỡ layout.
- Product detail responsive.
- Cart mobile không tràn.
- Checkout dễ đọc.
- Admin table có scroll nếu cần.
- Upload area dễ bấm.
- Focus ring rõ.
- Không có horizontal scroll toàn trang ngoài ý muốn.

## 12.3. Build check

Chạy:

    npm run build

Nếu có test:

    php artisan test

Nếu có lint format:

    npm run lint

hoặc command tương ứng trong project.

---

# 13. Acceptance criteria

Tính năng hoàn thành khi:

- Cursor đã phân tích screenshot người dùng đính kèm.
- Các lỗi layout nghiêm trọng được sửa trước.
- Website không bị horizontal scroll bất thường trên mobile.
- Product card nhìn đồng đều.
- Product card có hover effect ảnh scale nhẹ.
- Hover không làm layout nhảy.
- Button có hover/focus state rõ.
- Form input có focus state rõ.
- Cart và checkout responsive tốt hơn.
- Product detail responsive tốt hơn.
- Admin upload image area rõ ràng hơn.
- Không cài thêm UI/animation library.
- Không thay đổi nghiệp vụ.
- `npm run build` thành công.
- Có báo cáo file đã sửa và các điểm còn lại.

---

# 14. Prompt cho Cursor

Dùng prompt này sau khi đính kèm screenshot layout hiện tại.

    Read:
    @AGENTS.md
    @docs/SPEC.md
    @docs/UI_GUIDELINES.md
    @docs/DYNAMIC_UI.md
    @docs/EXTRA_UI_POLISH_CSS_RESPONSIVE_REVIEW.md

    I have attached screenshots showing current layout problems.

    Your task is to perform a UI polish, CSS audit, responsive review, and screenshot-based layout fix.

    Important:
    - Analyze the attached screenshots first.
    - Do not edit files immediately.
    - Identify visible layout issues from the screenshots.
    - Group issues by page and severity.
    - Map each issue to likely Blade/CSS/JS files.
    - Propose the smallest safe fixes.
    - Then implement fixes in small batches.

    Scope:
    - Use Tailwind CSS and existing CSS only.
    - Use jQuery only for existing interactions if needed.
    - Do not add any new UI library.
    - Do not add animation library.
    - Do not change backend business logic.
    - Do not change database.
    - Do not refactor unrelated code.
    - Preserve SSR fallback.
    - Improve responsive layout.
    - Improve hover, focus, transition, loading, and empty states.
    - Add product card hover effect where product image scales slightly on hover.
    - Ensure hover effects do not cause layout shift.
    - Ensure mobile layout does not overflow horizontally.

    Product card hover requirement:
    - Use Tailwind group hover.
    - Image container must use overflow-hidden.
    - Image should use object-contain.
    - Apply group-hover:scale-105 or similar.
    - Add subtle card shadow/translate hover.
    - Keep motion subtle and professional.

    Suggested implementation order:
    1. Header and page containers.
    2. Product card.
    3. Product listing grid.
    4. Product detail layout and gallery.
    5. Cart and checkout responsive fixes.
    6. Admin product form.
    7. Admin image upload area.
    8. Minor transitions and focus states.

    Before editing, report:
    - Screenshot findings.
    - Severity.
    - Files to inspect.
    - Files likely to change.
    - Fix plan.

    After editing:
    - Run npm run build.
    - Run php artisan test if relevant.
    - Report changed files.
    - Report remaining UI issues.
    - Mention which screenshot issues were fixed.

---

# 15. Gợi ý prompt ngắn cho từng screenshot

Nếu muốn xử lý từng ảnh một, dùng prompt này:

    Focus only on the attached screenshot for this page.

    1. Identify visible layout/UI problems.
    2. Explain why they hurt UX.
    3. Find the relevant Blade/CSS/JS files.
    4. Apply the smallest safe fix.
    5. Keep Tailwind and jQuery only.
    6. Do not change business logic.
    7. Run npm run build.
    8. Report before/after changes.

---

# 16. Gợi ý các lỗi nên ưu tiên sửa trước

Ưu tiên cao:

- Mobile horizontal overflow.
- Header che nội dung.
- Cart table tràn màn hình.
- Checkout khó đặt hàng trên mobile.
- Product detail không thấy nút mua trên mobile.
- Admin không upload được ảnh rõ ràng.
- Button quá nhỏ hoặc khó bấm.

Ưu tiên trung bình:

- Product card không đều.
- Spacing không nhất quán.
- Description khó đọc.
- Thumbnail gallery quá nhỏ.
- Table admin quá rối.

Ưu tiên thấp:

- Hover shadow.
- Transition.
- Icon polish.
- Empty state đẹp hơn.
- Animation nhẹ.

---

# 17. Không nên làm quá tay

Không nên:

- Đổi toàn bộ theme.
- Viết lại toàn bộ layout.
- Thêm nhiều màu mới.
- Thêm animation liên tục.
- Cài thư viện UI.
- Tự đổi logic cart.
- Tự đổi routes.
- Đổi database.
- Sửa mọi file cùng lúc.

Mục tiêu là polish có kiểm soát, không phải làm lại website.
