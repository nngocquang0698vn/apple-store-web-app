@props([
    'product',
    'images',
])

@php
    use App\Support\ProductImageUrl;

    $placeholder = asset('images/placeholders/product-placeholder.svg');
    $imageList = $images ?? collect();
    $hasImages = $imageList->isNotEmpty();
    $hasMultiple = $imageList->count() > 1;
    $firstImage = $imageList->first();
    $mainSrc = $firstImage ? ProductImageUrl::resolve($firstImage->path) : $placeholder;
    $mainAlt = $firstImage?->alt_text ?: $product->name;
@endphp

<section aria-label="Ảnh sản phẩm">
    <div
        @class([
            'relative w-full',
            'rounded-2xl border border-gray-200 bg-gray-50' => $hasImages || ! $hasMultiple,
        ])
        data-placeholder-src="{{ $placeholder }}"
        @if ($hasMultiple)
            data-carousel
            data-product-gallery
            data-carousel-mode="gallery"
            data-carousel-loop="false"
            tabindex="0"
            aria-roledescription="carousel"
            aria-label="Ảnh {{ $product->name }}"
        @endif
    >
        <div class="relative aspect-square overflow-hidden rounded-2xl bg-gray-50">
            <div class="flex h-full w-full items-center justify-center p-4" data-product-primary-image>
                <img
                    src="{{ $mainSrc }}"
                    alt="{{ $mainAlt }}"
                    class="h-full w-full object-contain"
                    width="600"
                    height="600"
                    @if ($hasImages) loading="eager" @else loading="lazy" @endif
                    data-carousel-main-image
                >
            </div>

            @if ($hasMultiple)
                <button
                    type="button"
                    data-carousel-prev
                    class="absolute left-2 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white/95 text-gray-700 shadow-sm transition duration-200 hover:border-blue-300 hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-40 sm:left-3 sm:h-11 sm:w-11"
                    aria-label="Ảnh trước"
                    disabled
                >
                    <i class="fa-solid fa-chevron-left text-sm" aria-hidden="true"></i>
                </button>
                <button
                    type="button"
                    data-carousel-next
                    class="absolute right-2 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white/95 text-gray-700 shadow-sm transition duration-200 hover:border-blue-300 hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-40 sm:right-3 sm:h-11 sm:w-11"
                    aria-label="Ảnh tiếp theo"
                >
                    <i class="fa-solid fa-chevron-right text-sm" aria-hidden="true"></i>
                </button>
            @endif
        </div>

        @if ($hasMultiple)
            <div class="border-t border-gray-100 bg-white px-3 py-3 sm:px-4">
                <div
                    class="flex gap-3 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
                    role="tablist"
                    aria-label="Chọn ảnh xem"
                >
                    @foreach ($imageList as $index => $image)
                        @php
                            $thumbSrc = ProductImageUrl::resolve($image->path);
                            $thumbAlt = $image->alt_text ?: $product->name;
                        @endphp
                        <button
                            type="button"
                            data-carousel-thumb
                            data-index="{{ $index }}"
                            data-image-src="{{ $thumbSrc }}"
                            data-image-alt="{{ $thumbAlt }}"
                            role="tab"
                            @class([
                                'flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border p-1 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 sm:h-24 sm:w-24',
                                'border-blue-500 bg-blue-50 ring-2 ring-blue-200' => $index === 0,
                                'border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50' => $index !== 0,
                            ])
                            aria-label="Ảnh {{ $index + 1 }}: {{ $thumbAlt }}"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        >
                            <img
                                src="{{ $thumbSrc }}"
                                alt=""
                                class="h-full w-full rounded-lg object-contain"
                                width="80"
                                height="80"
                                @if ($index > 0) loading="lazy" @endif
                            >
                        </button>
                    @endforeach
                </div>

                <p
                    class="mt-2 text-center text-xs text-gray-500"
                    data-carousel-indicator
                    aria-live="polite"
                >
                    1 / {{ $imageList->count() }}
                </p>
            </div>
        @endif
    </div>

    @if (! $hasImages)
        <p class="mt-2 text-center text-xs text-gray-500">Ảnh sản phẩm đang được cập nhật.</p>
    @endif
</section>
