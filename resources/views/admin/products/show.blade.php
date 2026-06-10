@extends('layouts.admin')

@section('title', $product->name . ' - Quản trị')
@section('heading', $product->name)

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-gray-600">
            <p>{{ $product->category?->name }} @if ($product->productSeries) / {{ $product->productSeries->name }} @endif</p>
            <p>{{ $product->is_active ? 'Đang hoạt động' : 'Đã tắt' }}{{ $product->is_featured ? ' / Nổi bật' : '' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Biến thể</a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Sửa sản phẩm</a>
        </div>
    </div>

    <section class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-base font-semibold text-gray-900">Thông tin</h2>
        <dl class="mt-4 grid gap-4 text-sm md:grid-cols-2">
            <div><dt class="text-gray-500">Slug</dt><dd class="mt-1 text-gray-900">{{ $product->slug }}</dd></div>
            <div><dt class="text-gray-500">Năm ra mắt</dt><dd class="mt-1 text-gray-900">{{ $product->release_year ?: '—' }}</dd></div>
            <div class="md:col-span-2"><dt class="text-gray-500">Mô tả ngắn</dt><dd class="mt-1 text-gray-900">{{ $product->short_description ?: '—' }}</dd></div>
        </dl>
    </section>

    <section class="mt-6 rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-base font-semibold text-gray-900">Tải ảnh</h2>
        <form method="post" action="{{ route('admin.products.images.store', $product->id) }}" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-4">
            @csrf
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700">File ảnh</label>
                <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="alt_text" class="block text-sm font-medium text-gray-700">Alt text</label>
                <input id="alt_text" name="alt_text" value="{{ old('alt_text') }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700">Thứ tự</label>
                <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_primary" value="0">
                <input type="checkbox" name="is_primary" value="1" class="rounded border-gray-300 text-blue-600">
                Ảnh chính
            </label>
            <div class="md:col-span-3">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Tải ảnh</button>
            </div>
        </form>
    </section>

    <section class="mt-6 rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-base font-semibold text-gray-900">Gallery</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($product->images as $image)
                <div class="rounded-lg border border-gray-200 p-4">
                    <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->alt_text }}" class="aspect-square w-full rounded-md bg-gray-50 object-contain">
                    <form method="post" action="{{ route('admin.product-images.update', $image) }}" class="mt-3 space-y-3">
                        @csrf
                        @method('PATCH')
                        <input name="alt_text" value="{{ old('alt_text', $image->alt_text) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $image->sort_order) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="is_primary" value="0">
                            <input type="checkbox" name="is_primary" value="1" class="rounded border-gray-300 text-blue-600" @checked($image->is_primary)>
                            Ảnh chính
                        </label>
                        <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Cập nhật ảnh</button>
                    </form>
                    <form method="post" action="{{ route('admin.product-images.destroy', $image) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-sm text-red-700 hover:bg-red-50">Xóa ảnh</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-500">Chưa có ảnh sản phẩm.</p>
            @endforelse
        </div>
    </section>
@endsection
