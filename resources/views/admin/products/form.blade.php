@php($product = $product ?? null)

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Danh mục</label>
        <select id="category_id" name="category_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <option value="">Chọn danh mục</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $product?->category_id) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="product_series_id" class="block text-sm font-medium text-gray-700">Dòng sản phẩm</label>
        <select id="product_series_id" name="product_series_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <option value="">Không chọn</option>
            @foreach ($seriesItems as $series)
                <option value="{{ $series->id }}" @selected((int) old('product_series_id', $product?->product_series_id) === $series->id)>{{ $series->name }}</option>
            @endforeach
        </select>
        @error('product_series_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
        <input id="name" name="name" value="{{ old('name', $product?->name) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $product?->slug) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="short_description" class="block text-sm font-medium text-gray-700">Mô tả ngắn</label>
    <textarea id="short_description" name="short_description" rows="2" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('short_description', $product?->short_description) }}</textarea>
    @error('short_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
        <textarea id="description" name="description" rows="6" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description', $product?->description) }}</textarea>
    </div>
    <div>
        <label for="specifications" class="block text-sm font-medium text-gray-700">Thông số</label>
        <textarea id="specifications" name="specifications" rows="6" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('specifications', $product?->specifications) }}</textarea>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-3">
    <div>
        <label for="release_year" class="block text-sm font-medium text-gray-700">Năm ra mắt</label>
        <input id="release_year" type="number" name="release_year" value="{{ old('release_year', $product?->release_year) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('release_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <label class="mt-6 flex items-center gap-2 text-sm text-gray-700">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600" @checked((bool) old('is_featured', $product?->is_featured ?? false))>
        Sản phẩm nổi bật
    </label>
    <label class="mt-6 flex items-center gap-2 text-sm text-gray-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600" @checked((bool) old('is_active', $product?->is_active ?? true))>
        Đang hoạt động
    </label>
</div>

<div class="flex gap-2">
    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Lưu</button>
    <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</a>
</div>
