@props(['product'])

@php
    $image = $product->images->first();
    $imageUrl = $image ? \App\Support\ProductImageUrl::resolve($image->path) : null;
    $minPrice = (int) ($product->variants_min_sale_price ?? 0);
    $maxPrice = (int) ($product->variants_max_sale_price ?? $minPrice);
@endphp

<article {{ $attributes->merge(['class' => 'product-card flex h-full flex-col overflow-hidden rounded-2xl']) }}>
    <a
        href="{{ route('products.show', $product) }}"
        class="product-card__media block overflow-hidden"
    >
        <x-product-image
            :src="$imageUrl"
            :alt="$image?->alt_text ?: $product->name"
            class="rounded-none"
        />
    </a>

    <div class="flex flex-1 flex-col p-4 sm:p-5">
        @if ($product->productSeries)
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                {{ $product->productSeries->name }}
            </p>
        @endif

        <h2 class="mt-1 line-clamp-2 text-sm font-semibold text-gray-900 sm:text-base">
            <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600 focus-visible:rounded-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                {{ $product->name }}
            </a>
        </h2>

        <div class="mt-3 flex items-center justify-between gap-2">
            <x-product-price :min-price="$minPrice" :max-price="$maxPrice" class="text-base font-bold text-gray-900" />
            @if ($product->is_featured)
                <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">
                    Nổi bật
                </span>
            @endif
        </div>

        <p class="mt-2 text-xs {{ $product->has_stock ? 'text-green-700' : 'text-red-600' }}">
            {{ $product->has_stock ? 'Còn hàng' : 'Hết hàng' }}
        </p>

        <div class="mt-4 mt-auto pt-2">
            <a
                href="{{ route('products.show', $product) }}"
                class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 transition duration-200 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
                Xem chi tiết
            </a>
        </div>
    </div>
</article>
