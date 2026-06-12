@php($variant = $variant ?? null)

<div class="sm:col-span-2">
    <label class="block text-sm font-medium text-gray-700">SKU</label>
    <input name="sku" value="{{ old('sku', $variant?->sku) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Màu</label>
    <select name="color_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <option value="">Không có</option>
        @foreach ($colors as $color)
            <option value="{{ $color->id }}" @selected((int) old('color_id', $variant?->color_id) === $color->id)>{{ $color->name }}</option>
        @endforeach
    </select>
    @error('color_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Dung lượng</label>
    <select name="storage_option_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <option value="">Không có</option>
        @foreach ($storageOptions as $storageOption)
            <option value="{{ $storageOption->id }}" @selected((int) old('storage_option_id', $variant?->storage_option_id) === $storageOption->id)>{{ $storageOption->label }}</option>
        @endforeach
    </select>
    @error('storage_option_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Giá niêm yết</label>
    <input type="number" min="0" name="original_price" value="{{ old('original_price', $variant?->original_price) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    @error('original_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Giá bán</label>
    <input type="number" min="0" name="sale_price" value="{{ old('sale_price', $variant?->sale_price) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    @error('sale_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Tồn kho</label>
    <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $variant?->stock_quantity ?? 0) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
    @error('stock_quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="flex items-end">
    <label class="flex w-full items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600" @checked((bool) old('is_active', $variant?->is_active ?? true))>
        Bật
    </label>
</div>
