@extends('layouts.admin')

@section('title', $product->name . ' - Quản trị')
@section('heading', $product->name)

@section('content')
    <div class="admin-page-header">
        <div class="text-sm text-gray-600">
            <p>{{ $product->category?->name }} @if ($product->productSeries) / {{ $product->productSeries->name }} @endif</p>
            <p>{{ $product->is_active ? 'Đang hoạt động' : 'Đã tắt' }}{{ $product->is_featured ? ' / Nổi bật' : '' }}</p>
        </div>
        <div class="admin-form-actions shrink-0 sm:justify-end">
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="admin-btn-secondary px-4 py-2">Biến thể</a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="admin-btn-primary">Sửa chi tiết sản phẩm</a>
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

    @include('admin.products._image-gallery', ['product' => $product])
@endsection

@push('vite')
    @vite(['resources/js/admin/product-images.js'])
@endpush
