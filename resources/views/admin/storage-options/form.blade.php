@php($storageOption = $storageOption ?? null)

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="label" class="block text-sm font-medium text-gray-700">Nhãn</label>
        <input id="label" name="label" value="{{ old('label', $storageOption?->label) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('label') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="capacity_gb" class="block text-sm font-medium text-gray-700">Dung lượng GB</label>
        <input id="capacity_gb" type="number" min="1" name="capacity_gb" value="{{ old('capacity_gb', $storageOption?->capacity_gb) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        @error('capacity_gb') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700">Thứ tự</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $storageOption?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <label class="mt-6 flex items-center gap-2 text-sm text-gray-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600" @checked((bool) old('is_active', $storageOption?->is_active ?? true))>
        Đang hoạt động
    </label>
</div>

<div class="admin-form-actions border-t border-gray-100 pt-6">
    <button type="submit" class="admin-btn-primary">Lưu</button>
    <a href="{{ route('admin.storage-options.index') }}" class="admin-btn-secondary px-4 py-2">Hủy</a>
</div>
