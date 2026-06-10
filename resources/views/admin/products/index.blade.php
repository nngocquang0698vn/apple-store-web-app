@extends('layouts.admin')

@section('title', 'Sản phẩm - Quản trị')
@section('heading', 'Sản phẩm')

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Quản lý sản phẩm, ảnh và biến thể bán hàng.</p>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Thêm sản phẩm</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Danh mục</th>
                    <th class="px-4 py-3">Dòng</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3">Dữ liệu</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="font-medium text-blue-700 hover:text-blue-900">{{ $product->name }}</a>
                            <p class="mt-1 text-xs text-gray-500">{{ $product->slug }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $product->category?->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $product->productSeries?->name ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <span class="block rounded-full px-2 py-1 text-xs font-medium {{ $product->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'Đang bật' : 'Đã tắt' }}
                                </span>
                                @if ($product->is_featured)
                                    <span class="block rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">Nổi bật</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $product->variants_count }} biến thể, {{ $product->images_count }} ảnh</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('admin.products.variants.index', $product->id) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Biến thể</a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Sửa</a>
                                <form method="post" action="{{ route('admin.products.destroy', $product->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-red-700 hover:bg-red-50">Ẩn</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Chưa có sản phẩm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
