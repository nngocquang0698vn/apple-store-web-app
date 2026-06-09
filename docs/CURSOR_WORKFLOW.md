# WORKFLOW LÀM VIỆC VỚI CURSOR

## 1. Mục tiêu

Dùng Cursor để tăng tốc nhưng vẫn giữ quyền kiểm soát:

- Phạm vi.
- Kiến trúc.
- Database.
- Bảo mật.
- Test.
- Git diff.

Không dùng prompt kiểu:

    Hãy làm toàn bộ website cho tôi.

## 2. Context chuẩn cho mỗi task

Luôn đưa:

- `@AGENTS.md`
- File spec liên quan.
- File code hiện tại liên quan.
- Test hiện tại liên quan.

Ví dụ:

    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/DATABASE.md
    @app/Models/Product.php
    @app/Queries/ProductQuery.php
    @tests/Feature/ProductDiscoveryTest.php

Không attach toàn bộ repository nếu không cần.

## 2.1. Proposal thư viện JavaScript

Nếu Cursor thấy cần thư viện mới, chỉ tạo proposal theo `docs/JS_LIBRARY_PROPOSAL_TEMPLATE.md`.

Không chạy npm install và không sửa package hoặc lock file trước khi human xác nhận.

## 3. Workflow 4 bước

### Bước 1: Explore

Yêu cầu Cursor đọc code và báo hiện trạng, chưa sửa file.

Prompt:

    Read @AGENTS.md and the relevant project documents.

    Inspect the current implementation for product listing and filtering.
    Do not edit any files.

    Report:
    1. Existing routes, controllers, models, views, and tests.
    2. Current database relationships.
    3. Missing requirements from the specification.
    4. Security and performance risks.
    5. The smallest next vertical slice.

### Bước 2: Plan

Prompt:

    Create an implementation plan for the next vertical slice.

    Constraints:
    - Laravel Blade SSR is canonical.
    - Use MySQL and Eloquent.
    - Do not add packages.
    - Do not add frontend frameworks.
    - Do not modify unrelated files.
    - Include validation, authorization, tests, and rollback strategy.

    The plan must list:
    1. Files to create.
    2. Files to modify.
    3. Database impact.
    4. Route impact.
    5. Security impact.
    6. Test cases.
    7. Acceptance criteria.

    Do not implement yet.

### Bước 3: Implement

Prompt:

    Implement only the approved vertical slice.

    Requirements:
    - Follow @AGENTS.md.
    - Keep controllers thin.
    - Use existing conventions.
    - Add or update tests.
    - Run focused tests.
    - Do not refactor unrelated code.
    - Do not add packages.
    - Stop and report if the specification conflicts with the existing code.

    At the end report:
    1. Files changed.
    2. Tests run and results.
    3. Assumptions.
    4. Remaining work.

### Bước 4: Review

Dùng chat mới hoặc model khác nếu có thể.

Prompt:

    Review the current git diff against:
    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/DATABASE.md
    @docs/ROUTES.md

    Do not edit files.

    Find:
    - Functional bugs
    - Missing requirements
    - Validation gaps
    - Authorization gaps
    - CSRF or XSS risks
    - SQL or mass-assignment risks
    - Price or inventory tampering
    - Race conditions
    - N+1 queries
    - Duplicate query logic
    - Unrelated changes
    - Missing tests

    Report findings by severity with exact file paths and suggested fixes.

## 4. Prompt cho product discovery

### 4.1. SSR trước

    Read @AGENTS.md, @docs/SPEC.md, @docs/ARCHITECTURE.md,
    @docs/DATABASE.md, and @docs/ROUTES.md.

    Implement the server-rendered product discovery vertical slice:
    - ProductFilterRequest
    - ProductQuery
    - ProductController integration
    - GET filter form
    - Search
    - Category, series, color, storage, price, stock, and featured filters
    - Whitelisted sorting
    - Pagination preserving query parameters
    - Empty state
    - Feature tests

    Do not implement AJAX.
    Do not modify admin features.
    Do not add packages.
    Ensure products are not duplicated by variant joins.

### 4.2. Review query

    Review ProductQuery and its tests.

    Verify:
    - Active product and active variant constraints
    - Search by name, series, description, and SKU
    - Combined filters
    - Minimum active variant price
    - Price sorting
    - No duplicate products
    - Pagination preserves filters
    - No N+1 queries
    - Sort is whitelisted
    - Invalid input behavior is consistent

    Do not edit code.

### 4.3. jQuery enhancement sau cùng

    Progressively enhance the existing SSR product filters using jQuery.

    Requirements:
    - Keep the normal GET form fully functional.
    - Reuse the same route and ProductQuery.
    - Do not duplicate filter logic in JavaScript.
    - Update the browser URL.
    - Support back and forward navigation.
    - Show loading and error states.
    - Return a Blade partial for the result area.
    - Do not add a JavaScript framework.

## 5. Prompt cho cart

    Implement the session cart vertical slice.

    Read:
    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/ROUTES.md

    Include:
    - CartService
    - Add, update, remove, and clear operations
    - SSR routes and forms
    - Server-side price and stock reload
    - Quantity validation
    - Flash messages
    - Feature tests

    Store only variant_id and quantity in the session.
    Ignore price or product data submitted by the client.
    Do not implement AJAX yet.

## 6. Prompt cho checkout

    Implement checkout and order placement as one complete vertical slice.

    Include:
    - Checkout Form Request
    - Checkout page
    - PlaceOrder action
    - Database transaction
    - Variant row locking
    - Price and stock revalidation
    - Order item snapshots
    - Inventory decrease
    - Cart clear only after commit
    - Success page
    - Feature tests for success, insufficient stock, and rollback

    Do not accept subtotal, total, price, status, or role from the client.

## 7. Prompt sửa bug

    Reproduce and fix this bug:

    [Describe exact bug]

    First:
    1. Identify the root cause.
    2. Point to the exact file and code path.
    3. Add a failing regression test.
    4. Make the smallest fix.
    5. Run the focused test and relevant suite.

    Do not refactor unrelated files.

## 8. Prompt refactor

Chỉ refactor khi feature đang chạy và có test.

    Refactor the selected code without changing behavior.

    Preconditions:
    - Existing tests must pass before editing.
    - Add characterization tests if behavior is unclear.

    Goals:
    - Reduce duplication.
    - Keep controllers thin.
    - Improve naming.
    - Preserve public routes and HTML behavior.

    Do not introduce new patterns or packages.
    Show the planned diff before editing.

## 9. Prompt security review

    Perform a security review of the current diff.

    Check:
    - Authentication
    - Authorization
    - CSRF
    - XSS
    - SQL injection
    - Mass assignment
    - File upload
    - Session handling
    - Price tampering
    - Inventory race conditions
    - Order ownership
    - Admin route protection

    Do not edit files.
    Report exploitable paths first.

## 10. Khi nào dùng Project Rules

Project Rules chứa chỉ dẫn lặp lại theo loại file.

Không đặt toàn bộ spec vào rule.

`AGENTS.md` giữ các nguyên tắc cốt lõi. Tài liệu `docs/` giữ yêu cầu chi tiết.

Chỉ thêm rule mới khi Cursor lặp lại cùng một lỗi nhiều lần.

## 11. Có cần Skill ngay không

Chưa cần.

Bắt đầu bằng:

- `AGENTS.md`
- `docs/`
- `.cursor/rules`
- Prompt theo vertical slice

Sau khi đã làm vài feature và nhận thấy một quy trình lặp lại ổn định, mới cân nhắc Skill như:

- `implement-laravel-feature`
- `review-laravel-feature`
- `generate-demo-data`

Không tạo Skill chỉ để chứa lại nội dung của `AGENTS.md`.

## 12. Git discipline

Trước khi Agent sửa:

    git status

Sau khi sửa:

    php artisan test
    npm run build
    git diff
    git status

Mỗi commit chỉ chứa một mục đích.

Không để Agent:

- Commit `.env`.
- Xóa migration đã chạy chỉ để sửa nhanh.
- Force push.
- Reset thay đổi chưa commit.
- Sửa lock file nếu không thay dependency.
- Đổi package ngoài task.

## 13. Dấu hiệu cần dừng Agent

Dừng và review khi Agent:

- Muốn thêm package.
- Muốn đổi framework.
- Muốn chuyển sang SPA.
- Muốn thêm repository pattern.
- Muốn viết lại toàn bộ schema.
- Sửa nhiều file ngoài plan.
- Xóa test.
- Tắt CSRF.
- Dùng raw SQL với input.
- Tin giá hoặc stock từ client.
- Không chạy test nhưng tuyên bố hoàn thành.

## 14. Definition of a good Cursor task

Một task tốt có:

- Một mục tiêu rõ.
- Một vertical slice.
- File context liên quan.
- Constraint.
- Acceptance criteria.
- Test command.
- Phạm vi không được sửa.

Ví dụ tốt:

    Implement server-rendered storage and color filters on /products.
    Preserve existing search and sorting.
    Use ProductQuery.
    Add combined-filter feature tests.
    Do not add AJAX or modify admin pages.

## 15. Prompt cho chiến lược hình ảnh ban đầu

    Read:
    @AGENTS.md
    @docs/IMAGE_STRATEGY.md
    @docs/UI_GUIDELINES.md

    Implement only the initial image foundation.

    Scope:
    - Copy the provided placeholder from:
      starter-assets/public/images/placeholders/product-placeholder.svg
    - To:
      public/images/placeholders/product-placeholder.svg
    - Do not download external images.
    - Do not hotlink images.
    - Do not add an image processing package.
    - Do not implement admin upload yet.
    - Do not create product migrations early.
    - If product models already exist, prepare a reusable display-image resolver without causing N+1 queries.
    - If product models do not exist, stop at the placeholder and reusable Blade presentation layer.

    Run relevant tests and npm build.
    Report files changed and remaining work.

## 16. Prompt cho admin image upload

Chỉ dùng sau khi Product và ProductImage đã tồn tại.

    Implement admin product image upload according to:
    @AGENTS.md
    @docs/IMAGE_STRATEGY.md
    @docs/DATABASE.md

    Requirements:
    - JPEG, PNG, and WebP only.
    - Server-generated filename.
    - Relative database path.
    - Product-scoped storage directory.
    - Primary image support.
    - Sort order support.
    - File deletion handled consistently.
    - Authorization and validation.
    - Feature tests.
    - No SVG upload.
    - No image processing package.
    - No external image URL field.

## Prompt dynamic cart

    Read:
    @AGENTS.md
    @docs/SPEC.md
    @docs/ARCHITECTURE.md
    @docs/ROUTES.md
    @docs/UI_GUIDELINES.md
    @docs/DYNAMIC_UI.md

    Progressively enhance the existing SSR cart with jQuery and AJAX.

    Requirements:
    - Keep normal HTML form fallbacks.
    - Reuse CartService.
    - Do not calculate authoritative totals in JavaScript.
    - Return integer VND unit price, line subtotal, cart subtotal, shipping fee, and grand total.
    - Update cart badge and visible totals without full reload.
    - Handle 419, 422, 409, and generic server errors.
    - Disable repeated actions while requests are pending.
    - Use Font Awesome Free for appropriate cart or loading icons.
    - Do not add another JavaScript library.
    - Add feature tests for JSON and server-side recalculation.

## Prompt dynamic checkout summary

    Enhance checkout summary with jQuery while keeping final order creation server-authoritative.

    Requirements:
    - Reuse CartService and pricing logic.
    - Add a summary endpoint only when needed.
    - Return canonical integer VND totals.
    - Update the visible summary without full reload.
    - Recalculate price, shipping, stock, and totals inside the final transaction.
    - Ignore client subtotal, shipping fee, and grand total.
    - Handle price and stock changes with Vietnamese messages.
    - Keep non-JavaScript checkout functional.

## Prompt UX and icon review

    Review customer-facing UI against @docs/UI_GUIDELINES.md and @docs/SPEC.md.

    Check navigation, search visibility, category access, cart visibility, Font Awesome consistency, accessibility, mobile usability, VND formatting, dynamic states, and unnecessary visual complexity.

    Do not edit files. Report findings by severity and file path.
