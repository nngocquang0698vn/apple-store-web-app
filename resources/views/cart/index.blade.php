@extends('layouts.app')

@section('title', 'Giỏ hàng - ' . config('app.name'))

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Giỏ hàng</h1>
            <p class="mt-2 text-sm text-gray-600" data-cart-header-count>
                {{ $cartCount }} sản phẩm trong giỏ
            </p>
        </div>

        @if ($items->isNotEmpty())
            <form method="post" action="{{ route('cart.destroy') }}" data-action="clear-cart" data-cart-clear-form>
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50"
                >
                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                    Xóa toàn bộ
                </button>
            </form>
        @endif
    </div>

    <div
        @class([
            'rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center',
            'hidden' => $items->isNotEmpty(),
        ])
        data-cart-empty-state
    >
            <i class="fa-solid fa-cart-shopping text-4xl text-gray-300" aria-hidden="true"></i>
            <p class="mt-4 text-base font-medium text-gray-900">Giỏ hàng trống</p>
            <p class="mt-2 text-sm text-gray-600">Hãy thêm sản phẩm để tiếp tục mua sắm.</p>
            <a
                href="{{ route('products.index') }}"
                class="mt-6 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Xem sản phẩm
            </a>
    </div>

    <div @class(['space-y-4', 'hidden' => $items->isEmpty()]) data-cart-items-container>
            @foreach ($items as $line)
                @php
                    /** @var \App\Models\ProductVariant $variant */
                    $variant = $line['variant'];
                    $product = $variant->product;
                    $image = $product?->images->first();
                    $imageUrl = $image ? \App\Support\ProductImageUrl::resolve($image->path) : null;
                @endphp

                <article
                    class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6"
                    data-cart-item="{{ $variant->id }}"
                    data-cart-item-variant-id="{{ $variant->id }}"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                        <a href="{{ $product ? route('products.show', $product) : '#' }}" class="shrink-0 sm:w-32">
                            <x-product-image
                                :src="$imageUrl"
                                :alt="$product?->name ?? 'Sản phẩm'"
                                class="rounded-lg"
                            />
                        </a>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">
                                        @if ($product)
                                            <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600">
                                                {{ $product->name }}
                                            </a>
                                        @else
                                            Sản phẩm không còn tồn tại
                                        @endif
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600">
                                        @if ($variant->color)
                                            Màu: {{ $variant->color->name }}
                                        @endif
                                        @if ($variant->storageOption)
                                            @if ($variant->color)
                                                ·
                                            @endif
                                            {{ $variant->storageOption->label }}
                                        @endif
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">SKU: {{ $variant->sku }}</p>
                                </div>

                                <p class="text-base font-semibold text-gray-900" data-line-unit-price="{{ $variant->id }}">
                                    <x-money :amount="$line['unit_price']" />
                                </p>
                            </div>

                            @if (! $line['is_purchasable'])
                                <p class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                                    Sản phẩm này hiện không khả dụng hoặc vượt tồn kho. Vui lòng cập nhật số lượng hoặc xóa khỏi giỏ.
                                </p>
                            @endif

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                                <form
                                    method="post"
                                    action="{{ route('cart.items.update', $variant) }}"
                                    class="flex items-center gap-2"
                                    data-action="update-cart-item"
                                    data-variant-id="{{ $variant->id }}"
                                    data-ajax="true"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <label for="quantity-{{ $variant->id }}" class="sr-only">Số lượng {{ $product?->name ?? 'sản phẩm' }}</label>
                                    <div class="inline-flex items-center rounded-lg border border-gray-300 bg-white">
                                        <button
                                            type="button"
                                            data-quantity-decrease
                                            class="inline-flex h-10 w-10 items-center justify-center text-gray-700 hover:bg-gray-50"
                                            aria-label="Giảm số lượng {{ $product?->name ?? 'sản phẩm' }}"
                                        >
                                            <i class="fa-solid fa-minus text-xs" aria-hidden="true"></i>
                                        </button>
                                        <input
                                            id="quantity-{{ $variant->id }}"
                                            name="quantity"
                                            type="number"
                                            min="1"
                                            max="{{ max(1, $variant->stock_quantity) }}"
                                            value="{{ $line['quantity'] }}"
                                            data-quantity-input="{{ $variant->id }}"
                                            data-last-valid-quantity="{{ $line['quantity'] }}"
                                            class="w-16 border-x border-gray-300 px-2 py-2 text-center text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        >
                                        <button
                                            type="button"
                                            data-quantity-increase
                                            class="inline-flex h-10 w-10 items-center justify-center text-gray-700 hover:bg-gray-50"
                                            aria-label="Tăng số lượng {{ $product?->name ?? 'sản phẩm' }}"
                                        >
                                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <span data-row-loading class="hidden text-sm text-gray-500" aria-live="polite">
                                        <i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                                        <span class="sr-only">Đang cập nhật</span>
                                    </span>
                                    <noscript>
                                        <button
                                            type="submit"
                                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            Cập nhật
                                        </button>
                                    </noscript>
                                </form>

                                <div class="flex items-center gap-4">
                                    <p class="text-sm text-gray-600">
                                        Tạm tính:
                                        <span class="font-semibold text-gray-900" data-line-subtotal="{{ $variant->id }}">
                                            <x-money :amount="$line['line_subtotal']" />
                                        </span>
                                    </p>

                                    <form
                                        method="post"
                                        action="{{ route('cart.items.destroy', $variant) }}"
                                        data-action="remove-cart-item"
                                        data-variant-id="{{ $variant->id }}"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-sm text-red-600 hover:bg-red-50"
                                            aria-label="Xóa sản phẩm khỏi giỏ"
                                        >
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
    </div>

    <div @class(['mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm', 'hidden' => $items->isEmpty()]) data-cart-summary-panel>
            <dl class="space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-gray-600">Tạm tính</dt>
                    <dd class="font-semibold text-gray-900" data-cart-subtotal>
                        <x-money :amount="$subtotal" />
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-gray-600">Phí vận chuyển</dt>
                    <dd class="font-semibold text-gray-900" data-cart-shipping>
                        <x-money :amount="$shippingFee" />
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-3 text-base">
                    <dt class="font-medium text-gray-900">Tổng cộng</dt>
                    <dd class="text-xl font-bold text-gray-900" data-cart-grand-total>
                        <x-money :amount="$grandTotal" />
                    </dd>
                </div>
            </dl>
            <p class="mt-4 text-xs text-gray-500">Giá được tính lại từ hệ thống khi xem giỏ hàng.</p>

            <div class="mt-6 flex flex-wrap gap-3">
                @auth
                    <a
                        href="{{ route('checkout.create') }}"
                        data-cart-checkout-button
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                    >
                        <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                        Thanh toán
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                        Đăng nhập để thanh toán
                    </a>
                @endauth
                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Tiếp tục mua sắm
                </a>
            </div>
    </div>
@endsection
