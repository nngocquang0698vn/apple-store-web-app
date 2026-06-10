@php($series = $series ?? null)

<div>
    <label for="category_id" class="block text-sm font-medium text-gray-700">Danh mục</label>
    <select id="category_id" name="category_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        <option value="">Chọn danh mục</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((int) old('category_id', $series?->category_id) === $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Tên dòng</label>
        <input id="name" name="name" value="{{ old('name', $series?->name) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="release_year" class="block text-sm font-medium text-gray-700">Năm ra mắt</label>
        <input id="release_year" type="number" name="release_year" value="{{ old('release_year', $series?->release_year) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('release_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
    <input id="slug" name="slug" value="{{ old('slug', $series?->slug) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700">Thứ tự</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $series?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <label class="mt-6 flex items-center gap-2 text-sm text-gray-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600" @checked((bool) old('is_active', $series?->is_active ?? true))>
        Đang hoạt động
    </label>
</div>

<div class="flex gap-2">
    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Lưu</button>
    <a href="{{ route('admin.product-series.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</a>
</div>
