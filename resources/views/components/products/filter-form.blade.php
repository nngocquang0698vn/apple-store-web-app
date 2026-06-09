@props([
    'filters',
    'categories',
    'seriesList',
    'colors',
    'storages',
])

<form
    method="get"
    action="{{ route('products.index') }}"
    data-product-filters
    {{ $attributes->merge(['class' => 'space-y-6']) }}
>
    <div>
        <label for="filter-q" class="block text-sm font-medium text-gray-700">Tìm kiếm</label>
        <input
            id="filter-q"
            type="search"
            name="q"
            value="{{ $filters['q'] ?? '' }}"
            placeholder="Tìm iPhone theo tên hoặc SKU"
            data-filter-search
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
        >
    </div>

    <div>
        <label for="filter-sort" class="block text-sm font-medium text-gray-700">Sắp xếp</label>
        <select
            id="filter-sort"
            name="sort"
            data-filter-auto-submit
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
        >
            @foreach ([
                'newest' => 'Mới nhất',
                'price_asc' => 'Giá thấp đến cao',
                'price_desc' => 'Giá cao đến thấp',
                'name_asc' => 'Tên A-Z',
                'name_desc' => 'Tên Z-A',
            ] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700">Danh mục</p>
        <div class="mt-2 space-y-2">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="category" value="" data-filter-auto-submit @checked(empty($filters['category']))>
                Tất cả
            </label>
            @foreach ($categories as $category)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="radio"
                        name="category"
                        value="{{ $category->slug }}"
                        data-filter-auto-submit
                        @checked(($filters['category'] ?? '') === $category->slug)
                    >
                    {{ $category->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700">Dòng sản phẩm</p>
        <div class="mt-2 space-y-2">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="radio" name="series" value="" data-filter-auto-submit @checked(empty($filters['series']))>
                Tất cả
            </label>
            @foreach ($seriesList as $series)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="radio"
                        name="series"
                        value="{{ $series->slug }}"
                        data-filter-auto-submit
                        @checked(($filters['series'] ?? '') === $series->slug)
                    >
                    {{ $series->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700">Màu sắc</p>
        <div class="mt-2 space-y-2">
            @foreach ($colors as $color)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        name="colors[]"
                        value="{{ $color->slug }}"
                        data-filter-auto-submit
                        @checked(in_array($color->slug, (array) ($filters['colors'] ?? []), true))
                    >
                    {{ $color->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700">Dung lượng</p>
        <div class="mt-2 space-y-2">
            @foreach ($storages as $storage)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        name="storages[]"
                        value="{{ $storage->capacity_gb }}"
                        data-filter-auto-submit
                        @checked(in_array($storage->capacity_gb, array_map('intval', (array) ($filters['storages'] ?? [])), true))
                    >
                    {{ $storage->label }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700">Khoảng giá (VNĐ)</p>
        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="filter-min-price" class="block text-xs text-gray-500">Tối thiểu</label>
                <input
                    id="filter-min-price"
                    type="number"
                    name="min_price"
                    min="0"
                    step="1000"
                    value="{{ $filters['min_price'] ?? '' }}"
                    placeholder="0"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                >
            </div>
            <div class="flex-1">
                <label for="filter-max-price" class="block text-xs text-gray-500">Tối đa</label>
                <input
                    id="filter-max-price"
                    type="number"
                    name="max_price"
                    min="0"
                    step="1000"
                    value="{{ $filters['max_price'] ?? '' }}"
                    placeholder="Không giới hạn"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                >
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input
                type="checkbox"
                name="in_stock"
                value="1"
                data-filter-auto-submit
                @checked(! empty($filters['in_stock']))
            >
            Chỉ sản phẩm còn hàng
        </label>
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input
                type="checkbox"
                name="featured"
                value="1"
                data-filter-auto-submit
                @checked(! empty($filters['featured']))
            >
            Chỉ sản phẩm nổi bật
        </label>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-stretch">
        <button
            type="submit"
            class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
        >
            <i class="fa-solid fa-filter" aria-hidden="true"></i>
            Áp dụng bộ lọc
        </button>
        <a
            href="{{ route('products.index') }}"
            class="inline-flex flex-1 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
            Xóa bộ lọc
        </a>
    </div>
</form>
