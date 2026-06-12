@extends('layouts.admin')

@section('title', 'Biến thể - Quản trị')
@section('heading', 'Biến thể: ' . $product->name)

@section('content')
    <div class="admin-page-header">
        <p class="text-sm text-gray-600">Mỗi biến thể là một đơn vị bán, có SKU, giá và tồn kho riêng.</p>
        <a href="{{ route('admin.products.show', $product->id) }}" class="admin-btn-secondary shrink-0 px-4 py-2">Quay lại sản phẩm</a>
    </div>

    <section class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-base font-semibold text-gray-900">Thêm biến thể</h2>
        <form method="post" action="{{ route('admin.products.variants.store', $product->id) }}" class="mt-4 space-y-4">
            @csrf
            <div class="admin-variant-grid">
                @include('admin.variants.fields')
            </div>
            <div class="admin-form-actions justify-end border-t border-gray-100 pt-4">
                <button type="submit" class="admin-btn-primary">Thêm biến thể</button>
            </div>
        </form>
    </section>

    <section class="admin-table-panel mt-6">
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
            <h2 class="text-sm font-semibold text-gray-900">Biến thể hiện có</h2>
        </div>

        @forelse ($product->variants as $variant)
            <article class="border-b border-gray-200 p-4 last:border-b-0 sm:p-5">
                <form id="variant-update-{{ $variant->id }}" method="post" action="{{ route('admin.variants.update', $variant) }}">
                    @csrf
                    @method('PATCH')
                    <div class="admin-variant-grid">
                        @include('admin.variants.fields', ['variant' => $variant])
                    </div>
                </form>

                <div class="admin-form-actions mt-4 border-t border-gray-100 pt-4 sm:justify-end">
                    <button type="submit" form="variant-update-{{ $variant->id }}" class="admin-btn-secondary">
                        Cập nhật
                    </button>
                    <form
                        method="post"
                        action="{{ route('admin.variants.destroy', $variant) }}"
                        onsubmit="return confirm('Tắt biến thể này?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-danger">Tắt biến thể</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="px-4 py-8 text-center text-sm text-gray-500">Chưa có biến thể.</p>
        @endforelse
    </section>
@endsection
