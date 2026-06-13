@props(['product'])

@auth
    @if (auth()->user()->canAccessAdmin())
        <div
            class="mb-6 flex flex-col gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between"
            role="region"
            aria-label="Công cụ quản trị sản phẩm"
        >
            <p class="text-sm text-blue-900">
                <i class="fa-solid fa-shield-halved mr-1.5" aria-hidden="true"></i>
                Bạn đang xem trang khách hàng với quyền quản trị.
            </p>
            <a
                href="{{ route('admin.products.show', $product->id) }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i>
                Quản lý sản phẩm
            </a>
        </div>
    @endif
@endauth
