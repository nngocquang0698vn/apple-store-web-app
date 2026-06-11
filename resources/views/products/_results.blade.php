<div class="hidden" data-mobile-filter-chips-source aria-hidden="true">
    <x-products.filter-category-chips
        :filters="$filters"
        :categories="$categories"
    />
</div>

<x-products.filter-summary
    :filters="$filters"
    :categories="$categories"
    :series-list="$seriesList"
    :colors="$colors"
    :storages="$storages"
    class="mb-4 lg:mb-6"
    data-mobile-filter-summary
/>

<div class="mb-4 hidden lg:block" data-desktop-filter-chips>
    <x-products.filter-category-chips
        :filters="$filters"
        :categories="$categories"
    />
</div>

<p class="mb-4 hidden text-sm text-gray-600 lg:block" data-product-count>{{ $products->total() }} sản phẩm</p>

@if ($products->isEmpty())
    <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
        <i class="fa-solid fa-box-open text-3xl text-gray-400" aria-hidden="true"></i>
        <h2 class="mt-4 text-lg font-semibold text-gray-900">Không tìm thấy sản phẩm</h2>
        <p class="mt-2 text-sm text-gray-600">
            Thử đổi từ khóa hoặc bộ lọc khác.
        </p>
        <a
            href="{{ route('products.index') }}"
            class="mt-6 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
        >
            Xóa bộ lọc
        </a>
    </div>
@else
    <div class="grid grid-cols-1 gap-4 min-[28rem]:grid-cols-2 sm:gap-5 lg:grid-cols-3">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <div class="mt-8" data-product-pagination>
        {{ $products->links() }}
    </div>
@endif
