@extends('layouts.app')

@section('title', 'Sản phẩm - ' . config('app.name'))

@section('content')
    <div data-products-index>
        <div class="mb-4 lg:mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Sản phẩm</h1>
            <p class="mt-2 text-sm text-gray-600">
                Khám phá iPhone, iPad và phụ kiện chính hãng (sạc, cáp, AirPods).
            </p>
        </div>

        <div class="mb-4 space-y-3 lg:hidden">
            <div data-mobile-filter-chips>
                <x-products.filter-category-chips
                    :filters="$filters"
                    :categories="$categories"
                />
            </div>

            <div class="flex items-center gap-2">
                <label for="filter-sort-mobile" class="sr-only">Sắp xếp sản phẩm</label>
                <select
                    id="filter-sort-mobile"
                    data-filter-sort-mobile
                    class="min-w-0 flex-1 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
                    @foreach ([
                        'newest' => 'Mới nhất',
                        'price_asc' => 'Giá thấp → cao',
                        'price_desc' => 'Giá cao → thấp',
                        'name_asc' => 'Tên A-Z',
                        'name_desc' => 'Tên Z-A',
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:border-blue-300 hover:bg-blue-50"
                    data-action="toggle-filter-drawer"
                    aria-expanded="false"
                    aria-controls="mobile-filter-drawer"
                >
                    <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                    Lọc
                </button>
            </div>

            <p class="text-sm text-gray-600" data-mobile-product-count>{{ $products->total() }} sản phẩm</p>
        </div>

        <div class="flex flex-col gap-8 lg:flex-row">
            <aside class="hidden w-full shrink-0 lg:block lg:w-72">
                <div class="sticky top-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h2 class="text-base font-semibold text-gray-900">Bộ lọc</h2>
                    <x-products.filter-form
                        :filters="$filters"
                        :categories="$categories"
                        :series-list="$seriesList"
                        :colors="$colors"
                        :storages="$storages"
                        variant="full"
                        class="mt-4"
                    />
                </div>
            </aside>

            <div
                id="mobile-filter-drawer"
                class="fixed inset-0 z-40 hidden lg:hidden"
                data-filter-drawer
                role="dialog"
                aria-modal="true"
                aria-label="Bộ lọc nâng cao"
            >
                <div class="absolute inset-0 bg-gray-900/40" data-action="close-filter-drawer"></div>
                <div class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-4">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Lọc nâng cao</h2>
                            <p class="mt-0.5 text-xs text-gray-500">Danh mục và sắp xếp ở trên danh sách</p>
                        </div>
                        <button
                            type="button"
                            class="rounded-xl border border-gray-300 p-2 text-gray-700 hover:bg-gray-50"
                            data-action="close-filter-drawer"
                            aria-label="Đóng bộ lọc"
                        >
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto px-4 py-4">
                        <x-products.filter-form
                            :filters="$filters"
                            :categories="$categories"
                            :series-list="$seriesList"
                            :colors="$colors"
                            :storages="$storages"
                            variant="mobile"
                        />
                    </div>
                </div>
            </div>

            <section class="relative min-w-0 flex-1">
                <div
                    class="pointer-events-none absolute inset-0 z-10 hidden items-center justify-center rounded-2xl bg-white/70"
                    data-product-results-loading
                    aria-hidden="true"
                >
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-blue-600"></i>
                </div>

                <div data-product-results>
                    @include('products._results')
                </div>
            </section>
        </div>
    </div>
@endsection
