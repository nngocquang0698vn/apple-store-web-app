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

    <x-admin-product-toolbar :product="$product" />

    <div
        class="grid min-w-0 gap-8 lg:grid-cols-2 lg:gap-10"
        data-product-detail
        data-selected-color="{{ $selectedColorSlug }}"
        data-selected-storage="{{ $selectedStorageGb }}"
    >
        <x-product-gallery :product="$product" :images="$product->images" />

        <section class="min-w-0">
            @if ($product->productSeries)
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">
                    {{ $product->productSeries->name }}
                </p>
            @endif

            <h1 class="mt-1 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

            @if ($product->short_description)
                <p class="mt-3 text-sm text-gray-600">{{ $product->short_description }}</p>
            @endif

            <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:sticky lg:top-4">
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

                        <div data-product-quantity-stepper>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Số lượng</label>
                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                <div class="inline-flex items-center rounded-lg border border-gray-300 bg-white">
                                    <button
                                        type="button"
                                        data-quantity-decrease
                                        @disabled(! $inStock)
                                        class="inline-flex h-10 w-10 items-center justify-center text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                                        aria-label="Giảm số lượng"
                                    >
                                        <i class="fa-solid fa-minus text-xs" aria-hidden="true"></i>
                                    </button>
                                    <input
                                        id="quantity"
                                        name="quantity"
                                        type="number"
                                        min="1"
                                        max="{{ max(1, $selectedVariant->stock_quantity) }}"
                                        value="{{ old('quantity', 1) }}"
                                        @disabled(! $inStock)
                                        data-product-quantity
                                        class="w-16 border-x border-gray-300 px-2 py-2 text-center text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 disabled:bg-gray-100"
                                    >
                                    <button
                                        type="button"
                                        data-quantity-increase
                                        @disabled(! $inStock)
                                        class="inline-flex h-10 w-10 items-center justify-center text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                                        aria-label="Tăng số lượng"
                                    >
                                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Tạm tính (ước tính):
                                    <span class="font-semibold text-gray-900" data-product-preview-subtotal>
                                        <x-money :amount="$selectedVariant->sale_price" />
                                    </span>
                                </p>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Giá chính thức được tính khi thêm vào giỏ.</p>
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
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:bg-gray-300"
                        >
                            <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                            <span data-add-cart-label>{{ $inStock ? 'Thêm vào giỏ hàng' : 'Hết hàng' }}</span>
                        </button>
                    </form>
                @endif
            </div>
        </section>
    </div>

    @php
        $specRows = [];

        if ($product->specifications) {
            $specText = trim($product->specifications);
            $lines = array_values(array_filter(
                array_map(trim(...), preg_split('/\r\n|\r|\n/', $specText)),
                static fn (string $line): bool => $line !== '',
            ));

            if (str_contains($specText, "\t")) {
                foreach ($lines as $row) {
                    $cells = array_map(trim(...), explode("\t", $row));

                    if (count($cells) >= 2) {
                        $specRows[] = ['label' => $cells[0], 'value' => implode(' · ', array_slice($cells, 1))];
                    } else {
                        $specRows[] = ['label' => null, 'value' => $row];
                    }
                }
            } elseif (collect($lines)->contains(static fn (string $line): bool => str_contains($line, ':'))) {
                foreach ($lines as $line) {
                    $parts = preg_split('/\s*:\s*/', $line, 2);
                    $specRows[] = isset($parts[1])
                        ? ['label' => $parts[0], 'value' => $parts[1]]
                        : ['label' => null, 'value' => $line];
                }
            } else {
                $index = 0;

                if (count($lines) % 2 === 1) {
                    $specRows[] = ['label' => null, 'value' => $lines[0]];
                    $index = 1;
                }

                while ($index < count($lines) - 1) {
                    $specRows[] = ['label' => $lines[$index], 'value' => $lines[$index + 1]];
                    $index += 2;
                }

                if ($index < count($lines)) {
                    $specRows[] = ['label' => null, 'value' => $lines[$index]];
                }
            }
        }

        $hasSpecRows = $specRows !== [];
    @endphp

    <div
        class="mt-10 grid min-w-0 gap-8 lg:mt-12 lg:grid-cols-10 lg:items-start lg:gap-10"
        data-product-detail-content
    >
        <section
            @class([
                'min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm md:p-8',
                'lg:col-span-7' => $hasSpecRows,
                'lg:col-span-10' => ! $hasSpecRows,
            ])
        >
            <h2 class="product-detail-section-title">Mô tả chi tiết</h2>
            @if ($product->description)
                <div class="product-description mt-4 max-w-none text-base leading-7 text-gray-700 md:mt-6">
                    {!! \App\Support\ProductDescriptionSanitizer::prepare($product->description) !!}
                </div>
            @else
                <p class="mt-4 text-sm leading-7 text-gray-600">
                    Thông tin chi tiết sản phẩm đang được cập nhật.
                </p>
            @endif
        </section>

        @if ($hasSpecRows)
            <section class="min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm md:p-8 lg:col-span-3">
                <h2 class="product-detail-section-title">Thông số kỹ thuật</h2>
                <dl class="mt-4 divide-y divide-gray-100 overflow-hidden rounded-xl border border-gray-100 bg-gray-50/50">
                    @foreach ($specRows as $row)
                        <div class="px-4 py-3">
                            @if ($row['label'])
                                <dt class="text-sm font-medium text-gray-900">
                                    {{ $row['label'] }}
                                </dt>
                                <dd class="mt-1 text-sm leading-relaxed text-gray-700">
                                    {{ $row['value'] }}
                                </dd>
                            @else
                                <dd class="text-sm leading-relaxed text-gray-700">
                                    {{ $row['value'] }}
                                </dd>
                            @endif
                        </div>
                    @endforeach
                </dl>
            </section>
        @endif
    </div>

    <script type="application/json" id="product-variants-data">
        @json($variantPayload)
    </script>
@endsection
