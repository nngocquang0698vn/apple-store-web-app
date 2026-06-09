# AGENTS.md

## Project identity

This repository contains a university web development project: a server-side rendered e-commerce website specializing in Apple products, including iPhone, iPad, iPod, and charging accessories.

The canonical project documents are:

- `docs/SPEC.md`
- `docs/ARCHITECTURE.md`
- `docs/DATABASE.md`
- `docs/ROUTES.md`
- `docs/UI_GUIDELINES.md`
- `docs/IMAGE_STRATEGY.md`
- `docs/DYNAMIC_UI.md`
- `docs/TASKS.md`

Read the relevant documents before planning or editing code.

## Locked technology choices

Use:

- PHP
- Laravel
- MySQL
- Laravel Blade
- Tailwind CSS
- jQuery as the default JavaScript library
- Font Awesome Free for approved UI icons
- Vite
- Laravel session authentication
- PHPUnit or Pest, following the existing repository

Do not introduce:

- React
- Vue
- Svelte
- Angular
- Livewire
- Inertia
- Alpine.js
- a separate REST frontend
- Elasticsearch
- Meilisearch
- Laravel Scout
- Redis as a required dependency
- a generic EAV product attribute system
- online payment gateways
- product reviews or comments

Do not change the framework version or add a package unless the task explicitly requires it and the benefit is documented.

## Dependency approval policy

jQuery is the default JavaScript library.

The agent may suggest another JavaScript library only when a concrete requirement cannot be solved reasonably with jQuery, vanilla JavaScript, HTML, CSS, or server-side rendering.

Before suggesting a library, provide:

- The exact problem.
- The solution using the existing stack.
- Package name and proposed version range.
- License.
- Bundle impact.
- Maintenance and security impact.
- Files and features affected.
- Removal or rollback strategy.

Do not install the package, modify `package.json`, modify a lock file, or write code that depends on it until a human explicitly approves.

Font Awesome Free is already approved. Do not add another icon library.

## Scope rules

The MVP includes:

- Customer and administrator roles
- Authentication and profile management
- Product catalog for iPhone, iPad, iPod, and charging accessories
- Product variants by color and storage
- Product search
- Product filtering and sorting
- Pagination
- Session-based cart
- COD checkout
- Customer order history
- Admin catalog management
- Admin inventory management
- Admin order processing
- Basic dashboard statistics

The MVP excludes:

- Product reviews and comments
- Coupons and loyalty points
- Online payment
- Shipping-provider integration
- Multiple warehouses
- Multiple sellers
- Chat
- Social login
- AI recommendations
- Complex analytics

Never implement excluded features unless the user explicitly changes the specification.

## Architecture principles

Use Laravel conventions and keep the design appropriate for a university project.

Preferred request flow:

    Request
    -> Middleware
    -> Form Request
    -> Controller
    -> Action, Service, or Query Object when needed
    -> Eloquent Model
    -> Database
    -> Blade View or Redirect

Rules:

- Keep controllers thin.
- Use Form Request classes for non-trivial validation.
- Use Policies for resource ownership and authorization.
- Use services or actions for multi-step workflows.
- Use a dedicated query object for product search, filter, sort, and pagination.
- Do not add a repository layer over Eloquent unless a real need is demonstrated.
- Do not place business logic in Blade templates.
- Do not place business logic in jQuery.
- Do not use route closures for application features.
- Prefer clear code over clever abstractions.

## Currency rules

The only storefront currency is Vietnamese đồng.

- Store prices and totals as integer VND values.
- Never use float or double for money.
- Do not store formatted price strings.
- Format customer-facing values such as `19.990.000 ₫`.
- Use one reusable money formatter or Blade component.
- Recalculate all authoritative totals on the server.
- Do not implement currency conversion in the MVP.

## Product catalog rules

- A product is a model such as `iPhone 16 Pro`, `iPad Air`, `iPod touch`, or `Apple 20W USB-C Power Adapter`.
- A product variant is the purchasable unit. Color and storage are optional because charging accessories may not have storage.
- Cart items reference product variants, not products.
- SKU must be unique.
- Product slugs must be unique.
- Only active products and active variants are public.
- Product cards display the minimum active variant price.
- Inventory is stored on product variants.
- Product deletion should normally use soft deletes or deactivation after order history exists.

## Search and filter rules

Use one public products endpoint with query parameters.

Supported parameters are defined in `docs/SPEC.md` and `docs/ROUTES.md`.

Core requirements:

- Search and filtering must work without JavaScript.
- Use a normal GET form.
- Preserve filters across pagination.
- Reset page to 1 when filter criteria change.
- Whitelist sort values.
- Ignore or reject invalid filter values consistently.
- Centralize query construction in `ProductQuery` or an equivalent class.
- Do not duplicate search logic in controllers, Blade, or jQuery.
- Use MySQL and Eloquent only for the MVP.
- Avoid unnecessary joins that duplicate products.
- Use eager loading and aggregate subqueries when appropriate.

jQuery may progressively enhance the filter form, but the server-rendered flow remains canonical.

## Dynamic website rules

The project is server-side rendered but must provide a dynamic customer experience through jQuery and AJAX.

Use jQuery for:

- Product variant selection.
- Dynamic price, image, and stock display.
- Product search, filtering, sorting, and pagination enhancement.
- Add-to-cart.
- Cart quantity updates and item removal.
- Cart badge updates.
- Checkout summary refresh.

Rules:

- Core flows must retain normal HTML form and SSR fallbacks.
- JavaScript may calculate an immediate preview, but that preview is never authoritative.
- Cart and checkout UI should replace previews with canonical values returned by Laravel.
- AJAX and non-AJAX requests must call the same Services, Actions, and Query objects.
- Do not duplicate price, stock, cart, shipping, or order logic in JavaScript.
- Handle loading, success, validation, conflict, session expiry, and server error states.
- Disable repeated UI actions while a request is pending, while also protecting duplicate operations on the server.

## Cart rules

- Store only `variant_id` and `quantity` in the session.
- Do not trust product names, prices, totals, stock, or status from the client.
- Re-read variants from the database before rendering totals and before checkout.
- A variant appears at most once in the cart.
- Quantity must be a positive integer.
- Quantity must not exceed available stock.
- A disabled or deleted product or variant cannot be newly added to the cart.

## Checkout and inventory rules

Checkout must:

1. Require authentication.
2. Validate shipping information.
3. Reject an empty cart.
4. Start a database transaction.
5. Lock selected product variant rows for update.
6. Re-check active status, price, and stock.
7. Create the order.
8. Create immutable order item snapshots.
9. Decrease stock.
10. Commit.
11. Clear the cart only after a successful commit.

On failure:

- Roll back the transaction.
- Do not create a partial order.
- Do not partially change inventory.
- Return a useful Vietnamese error message.

Order cancellation must restore inventory at most once.

## Customer experience and icon rules

The customer storefront must be friendly, clear, and task-oriented.

- Keep category navigation, search, account, and cart easy to find.
- Use responsive layouts with clear visual hierarchy.
- Show useful empty, loading, success, and error states.
- Use Font Awesome Free for appropriate icons such as cart, search, account, filter, menu, quantity controls, and order status.
- Icons support comprehension; they do not replace necessary text.
- Icon-only controls require an accessible name.
- Do not use Apple brand icons or visual claims that imply official affiliation.

## Security requirements

Always consider:

- Authentication
- Authorization
- CSRF protection
- XSS escaping
- SQL injection prevention
- Mass assignment
- Session fixation
- Ownership checks
- File upload validation
- Price tampering
- Inventory race conditions
- Open redirects
- Sensitive data exposure

Specific rules:

- Use `{{ }}` for user-controlled output in Blade.
- Do not use raw Blade output for user input.
- Do not concatenate user input into SQL.
- Use `$request->validated()` instead of `$request->all()` for writes.
- Never accept `role`, `status`, `price`, `stock`, or `total_amount` from public customer forms unless explicitly allowed.
- Regenerate the session after login.
- Invalidate the session and regenerate the CSRF token after logout.
- Protect admin routes with authentication and admin authorization.
- Use Policies for customer order access.
- Never read, display, log, or commit secrets from `.env`.

## Frontend requirements

- Use Blade layouts and Blade components for repeated UI.
- Use semantic HTML and accessible form labels.
- Use Tailwind CSS with a mobile-first approach.
- Use Vietnamese user-facing text.
- Use jQuery only where it gives clear value.
- Keep core navigation and forms functional without JavaScript.
- Avoid inline JavaScript and inline event handlers.
- Use data attributes to connect Blade markup and jQuery behavior.
- Show validation errors near the related field.
- Provide loading, empty, success, and error states.
- Do not imitate Apple trademarks in a misleading way; this is an academic storefront.

## Product image rules

Follow `docs/IMAGE_STRATEGY.md`.

- Use a local SVG placeholder from Phase 0.
- Do not use external image hotlinks.
- Store only relative paths in the database.
- Product cards use a stable square container and `object-contain`.
- Resolve images in this order: primary image, first sorted image, local placeholder.
- Do not query images from Blade.
- Eager load the image data needed by product listings.
- Do not create an accessor that silently causes N+1 queries.
- Use `loading="lazy"` for product card images.
- Do not upload SVG files through the admin in the MVP.
- Validate MIME type, extension, and size for uploaded JPEG, PNG, or WebP images.
- Generate server-side filenames.
- Do not add an image processing package unless explicitly requested.
- Seed data must still work when optional demo images are absent.

## Testing requirements

For every important feature, include:

- A successful feature test.
- A validation failure test.
- An authorization or ownership failure test when relevant.
- Database assertions for writes.
- Inventory assertions for checkout and cancellation.
- Search and filter combination tests.

Before declaring a task complete, run the commands appropriate to the repository, normally:

    php artisan test
    npm run build

Also inspect:

    git diff
    git status

Do not disable tests to make a task pass.

## Agent working procedure

For a non-trivial task:

1. Read the relevant documentation.
2. Inspect existing code and conventions.
3. State assumptions.
4. Produce a concise implementation plan.
5. List files to create or modify.
6. Identify database, security, and testing impacts.
7. Implement the smallest complete vertical slice.
8. Run focused tests.
9. Run the full relevant test suite.
10. Review the diff for unrelated changes.
11. Summarize changes, tests, and remaining limitations.

Do not modify unrelated files.

Do not perform broad refactors during a feature task unless explicitly requested.

Do not claim completion when tests fail or when a required part is missing.

## Definition of Done

A feature is complete only when:

- It matches the specification.
- Validation is implemented.
- Authorization is implemented where needed.
- Error states are handled.
- Server-rendered behavior works.
- Responsive UI is acceptable.
- Relevant tests pass.
- No debug code remains.
- No secrets are exposed.
- No unrelated files are changed.
- Documentation is updated when behavior or architecture changes.
