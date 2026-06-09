<x-products.filter-summary
    :filters="$filters"
    :categories="$categories"
    :series-list="$seriesList"
    :colors="$colors"
    :storages="$storages"
    class="mb-6 hidden lg:block"
/>

<p class="mb-4 hidden text-sm text-gray-600 lg:block" data-product-count>{{ $products->total() }} sản phẩm</p>
<p class="mb-4 text-sm text-gray-600 lg:hidden" data-product-count>{{ $products->total() }} sản phẩm</p>

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
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <div class="mt-8" data-product-pagination>
        {{ $products->links() }}
    </div>
@endif
