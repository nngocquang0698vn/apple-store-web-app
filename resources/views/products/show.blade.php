@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
    @php
        $selectedColorSlug = $selectedVariant?->color?->slug;
        $selectedStorageGb = $selectedVariant?->storageOption?->capacity_gb;
        $inStock = $selectedVariant && $selectedVariant->stock_quantity > 0;
        $allStorages = $selector->storagesForColor(null);
    @endphp

    <nav class="mb-6 text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ route('products.index') }}" class="hover:text-blue-600">Sản phẩm</a></li>
            @if ($product->category)
                <li aria-hidden="true">/</li>
                <li>
                    <a
                        href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                        class="hover:text-blue-600"
                    >
                        {{ $product->category->name }}
                    </a>
                </li>
            @endif
            <li aria-hidden="true">/</li>
            <li class="font-medium text-gray-900" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div
        class="grid gap-10 lg:grid-cols-2"
        data-product-detail
        data-selected-color="{{ $selectedColorSlug }}"
        data-selected-storage="{{ $selectedStorageGb }}"
    >
        <section aria-label="Ảnh sản phẩm">
            <x-product-image
                :src="$primaryImageUrl"
                :alt="$product->images->first()?->alt_text ?: $product->name"
                :lazy="false"
                class="rounded-2xl border border-gray-200 bg-white"
                data-product-primary-image
            />

            @if ($product->images->count() > 1)
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach ($product->images as $image)
                        <x-product-image
                            :src="\App\Support\ProductImageUrl::resolve($image->path)"
                            :alt="$image->alt_text ?: $product->name"
                            class="rounded-lg border border-gray-200"
                        />
                    @endforeach
                </div>
            @endif
        </section>

        <section>
            @if ($product->productSeries)
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">
                    {{ $product->productSeries->name }}
                </p>
            @endif

            <h1 class="mt-1 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

            @if ($product->short_description)
                <p class="mt-3 text-sm text-gray-600">{{ $product->short_description }}</p>
            @endif

            <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                @if ($selectedVariant)
                    <div class="flex flex-wrap items-end gap-3">
                        <p class="text-2xl font-bold text-gray-900" data-product-sale-price>
                            <x-money :amount="$selectedVariant->sale_price" />
                        </p>
                        @if ($selectedVariant->original_price && $selectedVariant->original_price > $selectedVariant->sale_price)
                            <p class="text-sm text-gray-500 line-through" data-product-original-price>
                                <x-money :amount="$selectedVariant->original_price" />
                            </p>
                        @else
                            <p class="hidden text-sm text-gray-500 line-through" data-product-original-price></p>
                        @endif
                    </div>

                    <p
                        class="mt-2 text-sm {{ $inStock ? 'text-green-700' : 'text-red-600' }}"
                        data-product-stock
                    >
                        @if ($inStock)
                            Còn {{ $selectedVariant->stock_quantity }} sản phẩm
                        @else
                            Hết hàng
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-gray-500" data-product-sku>SKU: {{ $selectedVariant->sku }}</p>
                @endif

                @if ($selector->colors()->isNotEmpty())
                    <div class="mt-6">
                        <h2 class="text-sm font-semibold text-gray-900">Màu sắc</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($selector->colors() as $color)
                                @php
                                    $isSelected = $selectedColorSlug === $color->slug;
                                    $colorUrl = route('products.show', [
                                        'product' => $product,
                                        'color' => $color->slug,
                                        'storage' => $selectedStorageGb,
                                    ]);
                                @endphp
                                <a
                                    href="{{ $colorUrl }}"
                                    data-action="select-color"
                                    data-color-slug="{{ $color->slug }}"
                                    @class([
                                        'inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium transition',
                                        'border-blue-600 bg-blue-50 text-blue-700' => $isSelected,
                                        'border-gray-300 bg-white text-gray-700 hover:border-gray-400' => ! $isSelected,
                                    ])
                                    aria-current="{{ $isSelected ? 'true' : 'false' }}"
                                >
                                    <span
                                        class="h-4 w-4 rounded-full border border-gray-300"
                                        style="background-color: {{ $color->hex_code }}"
                                        aria-hidden="true"
                                    ></span>
                                    {{ $color->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($allStorages->isNotEmpty())
                    <div class="mt-6">
                        <h2 class="text-sm font-semibold text-gray-900">Dung lượng</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($allStorages as $storage)
                                @php
                                    $isSelected = $selectedStorageGb === $storage->capacity_gb;
                                    $isAvailable = $selector->hasCombination($selectedColorSlug, $storage->capacity_gb);
                                    $storageUrl = route('products.show', [
                                        'product' => $product,
                                        'color' => $selectedColorSlug,
                                        'storage' => $storage->capacity_gb,
                                    ]);
                                @endphp
                                <a
                                    href="{{ $storageUrl }}"
                                    data-action="select-storage"
                                    data-storage-gb="{{ $storage->capacity_gb }}"
                                    @class([
                                        'rounded-lg border px-4 py-2 text-sm font-medium transition',
                                        'border-blue-600 bg-blue-50 text-blue-700' => $isSelected && $isAvailable,
                                        'border-gray-300 bg-white text-gray-700 hover:border-gray-400' => $isAvailable && ! $isSelected,
                                        'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed opacity-60' => ! $isAvailable,
                                    ])
                                    @if (! $isAvailable) aria-disabled="true" @endif
                                    aria-current="{{ $isSelected ? 'true' : 'false' }}"
                                >
                                    {{ $storage->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($selectedVariant)
                    <form
                        action="{{ route('cart.items.store') }}"
                        method="post"
                        class="mt-8 space-y-4"
                        data-action="add-to-cart"
                    >
                        @csrf
                        <input type="hidden" name="variant_id" value="{{ $selectedVariant->id }}" data-product-variant-id>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Số lượng</label>
                            <input
                                id="quantity"
                                name="quantity"
                                type="number"
                                min="1"
                                max="{{ max(1, $selectedVariant->stock_quantity) }}"
                                value="{{ old('quantity', 1) }}"
                                @disabled(! $inStock)
                                data-product-quantity
                                class="mt-1 w-28 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 disabled:bg-gray-100"
                            >
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('variant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            data-product-add-button
                            @disabled(! $inStock)
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                        >
                            <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                            <span data-add-cart-label>{{ $inStock ? 'Thêm vào giỏ hàng' : 'Hết hàng' }}</span>
                        </button>
                    </form>
                @endif
            </div>
        </section>
    </div>

    @if ($product->description)
        <section class="mt-12 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Mô tả chi tiết</h2>
            <div class="product-description mt-4 text-sm text-gray-700">
                {!! \App\Support\ProductDescriptionSanitizer::prepare($product->description) !!}
            </div>
        </section>
    @endif

    @if ($product->specifications)
        <section class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Thông số kỹ thuật</h2>
            <div class="prose prose-sm mt-4 max-w-none text-gray-700">
                {!! nl2br(e($product->specifications)) !!}
            </div>
        </section>
    @endif

    <script type="application/json" id="product-variants-data">
        @json($variantPayload)
    </script>
@endsection
