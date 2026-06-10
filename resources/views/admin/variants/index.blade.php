@extends('layouts.admin')

@section('title', 'Biến thể - Quản trị')
@section('heading', 'Biến thể: ' . $product->name)

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Mỗi biến thể là một đơn vị bán, có SKU, giá và tồn kho riêng.</p>
        <a href="{{ route('admin.products.show', $product->id) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Quay lại sản phẩm</a>
    </div>

    <section class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-base font-semibold text-gray-900">Thêm biến thể</h2>
        <form method="post" action="{{ route('admin.products.variants.store', $product->id) }}" class="mt-4 grid gap-4 lg:grid-cols-8">
            @csrf
            @include('admin.variants.fields')
            <div class="lg:col-span-8">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Thêm biến thể</button>
            </div>
        </form>
    </section>

    <section class="mt-6 overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Màu</th>
                    <th class="px-4 py-3">Dung lượng</th>
                    <th class="px-4 py-3">Giá</th>
                    <th class="px-4 py-3">Kho</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($product->variants as $variant)
                    <tr>
                        <td colspan="6" class="px-4 py-4">
                            <form method="post" action="{{ route('admin.variants.update', $variant) }}" class="grid gap-3 lg:grid-cols-8">
                                @csrf
                                @method('PATCH')
                                @include('admin.variants.fields', ['variant' => $variant])
                                <div class="flex items-end gap-2 lg:col-span-8">
                                    <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Cập nhật</button>
                                </div>
                            </form>
                            <form method="post" action="{{ route('admin.variants.destroy', $variant) }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-red-700 hover:bg-red-50">Tắt biến thể</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Chưa có biến thể.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
