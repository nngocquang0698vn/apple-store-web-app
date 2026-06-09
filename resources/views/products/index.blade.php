@extends('layouts.app')

@section('title', 'Sản phẩm - ' . config('app.name'))

@section('content')
    <div data-products-index>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Sản phẩm</h1>
            <p class="mt-2 text-sm text-gray-600">
                Khám phá iPhone, iPad, iPod và phụ kiện sạc chính hãng.
            </p>
        </div>

        <div class="mb-4 flex items-center justify-between gap-4 lg:hidden">
            <p class="text-sm text-gray-600" data-mobile-product-count>{{ $products->total() }} sản phẩm</p>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700"
                data-action="toggle-filter-drawer"
                aria-expanded="false"
                aria-controls="mobile-filter-drawer"
            >
                <i class="fa-solid fa-filter" aria-hidden="true"></i>
                Bộ lọc
            </button>
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
                aria-label="Bộ lọc sản phẩm"
            >
                <div class="absolute inset-0 bg-gray-900/40" data-action="close-filter-drawer"></div>
                <div class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-4">
                        <h2 class="text-base font-semibold text-gray-900">Bộ lọc</h2>
                        <button
                            type="button"
                            class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700"
                            data-action="close-filter-drawer"
                            aria-label="Đóng bộ lọc"
                        >
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4">
                        <x-products.filter-form
                            :filters="$filters"
                            :categories="$categories"
                            :series-list="$seriesList"
                            :colors="$colors"
                            :storages="$storages"
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
