@props(['products'])

@php
    $hasProducts = $products->isNotEmpty();
@endphp

@if (! $hasProducts)
    <div class="mx-auto w-full max-w-md">
        <x-product-image alt="Minh họa sản phẩm" :lazy="false" />
        <p class="mt-3 text-center text-sm text-gray-500">
            Sản phẩm sẽ hiển thị tại đây khi có ảnh trong hệ thống.
        </p>
    </div>
@else
    <div
        class="relative w-full"
        data-home-showcase
        data-autoplay-ms="5000"
        aria-roledescription="carousel"
        aria-label="Sản phẩm nổi bật"
    >
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-b from-gray-50 to-white ring-1 ring-gray-200/80">
            <div class="relative aspect-square sm:aspect-[4/3]">
                @foreach ($products as $index => $product)
                    @php
                        $image = $product->images->first();
                        $imageUrl = $image
                            ? \App\Support\ProductImageUrl::resolve($image->path)
                            : asset('images/placeholders/product-placeholder.svg');
                        $isActive = $index === 0;
                    @endphp
                    <article
                        data-showcase-slide
                        data-index="{{ $index }}"
                        @class([
                            'absolute inset-0 flex flex-col transition-opacity duration-500 ease-in-out',
                            'pointer-events-none opacity-0' => ! $isActive,
                            'pointer-events-auto opacity-100' => $isActive,
                        ])
                        @if ($isActive) aria-hidden="false" @else aria-hidden="true" @endif
                    >
                        <a
                            href="{{ route('products.show', $product) }}"
                            class="group flex min-h-0 flex-1 flex-col items-center justify-center px-6 pt-8 sm:px-10 sm:pt-10"
                        >
                            <div class="relative w-full max-w-xs flex-1 sm:max-w-sm">
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $image?->alt_text ?: $product->name }}"
                                    class="mx-auto h-full max-h-56 w-full object-contain transition-transform duration-300 group-hover:scale-[1.02] sm:max-h-64"
                                    width="400"
                                    height="400"
                                    @if ($index > 0) loading="lazy" @endif
                                >
                            </div>

                            <div class="mt-4 w-full pb-6 text-center sm:pb-8">
                                @if ($product->productSeries)
                                    <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                                        {{ $product->productSeries->name }}
                                    </p>
                                @endif
                                <h2 class="mt-1 text-lg font-semibold text-gray-900 sm:text-xl">
                                    {{ $product->name }}
                                </h2>
                                <div class="mt-2 flex flex-col items-center gap-2 sm:flex-row sm:justify-center sm:gap-3">
                                    <x-product-price
                                        :min-price="$product->variants_min_sale_price ?? 0"
                                        :max-price="$product->variants_max_sale_price ?? null"
                                        class="text-base"
                                    />
                                    <span class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 group-hover:underline">
                                        Xem chi tiết
                                        <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach

                @if ($products->count() > 1)
                    <button
                        type="button"
                        data-showcase-prev
                        class="absolute left-2 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-gray-700 shadow-sm backdrop-blur hover:bg-white sm:left-3 sm:h-10 sm:w-10"
                        aria-label="Sản phẩm trước"
                    >
                        <i class="fa-solid fa-chevron-left text-sm" aria-hidden="true"></i>
                    </button>
                    <button
                        type="button"
                        data-showcase-next
                        class="absolute right-2 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-gray-700 shadow-sm backdrop-blur hover:bg-white sm:right-3 sm:h-10 sm:w-10"
                        aria-label="Sản phẩm tiếp theo"
                    >
                        <i class="fa-solid fa-chevron-right text-sm" aria-hidden="true"></i>
                    </button>
                @endif
            </div>

            @if ($products->count() > 1)
                <div class="border-t border-gray-100 bg-white/80 px-3 py-3 sm:px-4">
                    <div
                        class="flex gap-2 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
                        data-showcase-thumbs
                        role="tablist"
                        aria-label="Chọn sản phẩm xem"
                    >
                        @foreach ($products as $index => $product)
                            @php
                                $thumb = $product->images->first();
                                $thumbUrl = $thumb
                                    ? \App\Support\ProductImageUrl::resolve($thumb->path)
                                    : asset('images/placeholders/product-placeholder.svg');
                            @endphp
                            <button
                                type="button"
                                data-showcase-thumb
                                data-index="{{ $index }}"
                                role="tab"
                                @class([
                                    'flex shrink-0 flex-col items-center gap-1 rounded-xl border p-2 transition sm:w-20',
                                    'border-blue-500 bg-blue-50 ring-2 ring-blue-200' => $index === 0,
                                    'border-gray-200 bg-white hover:border-gray-300' => $index !== 0,
                                ])
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="{{ $product->name }}"
                            >
                                <span class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-lg bg-gray-50 sm:h-14 sm:w-14">
                                    <img
                                        src="{{ $thumbUrl }}"
                                        alt=""
                                        class="h-full w-full object-contain"
                                        width="56"
                                        height="56"
                                        loading="lazy"
                                    >
                                </span>
                                <span class="max-w-[4.5rem] truncate text-[10px] font-medium text-gray-700 sm:max-w-[5rem] sm:text-xs">
                                    {{ $product->name }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-2 flex justify-center gap-1.5" data-showcase-dots>
                        @foreach ($products as $index => $product)
                            <button
                                type="button"
                                data-showcase-dot
                                data-index="{{ $index }}"
                                @class([
                                    'h-1.5 rounded-full transition-all',
                                    'w-5 bg-blue-600' => $index === 0,
                                    'w-1.5 bg-gray-300 hover:bg-gray-400' => $index !== 0,
                                ])
                                aria-label="Slide {{ $index + 1 }}: {{ $product->name }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            ></button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <p class="mt-2 text-center text-xs text-gray-500" data-showcase-status aria-live="polite">
            {{ $products->first()->name }} — 1 / {{ $products->count() }}
        </p>
    </div>
@endif
