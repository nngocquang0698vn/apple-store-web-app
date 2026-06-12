@php($category = $category ?? null)

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Tên danh mục</label>
    <input id="name" name="name" value="{{ old('name', $category?->name) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
    <input id="slug" name="slug" value="{{ old('slug', $category?->slug) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
    <textarea id="description" name="description" rows="4" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description', $category?->description) }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700">Thứ tự</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <label class="mt-6 flex items-center gap-2 text-sm text-gray-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600" @checked((bool) old('is_active', $category?->is_active ?? true))>
        Đang hoạt động
    </label>
</div>

<div class="admin-form-actions border-t border-gray-100 pt-6">
    <button type="submit" class="admin-btn-primary">Lưu</button>
    <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary px-4 py-2">Hủy</a>
</div>
