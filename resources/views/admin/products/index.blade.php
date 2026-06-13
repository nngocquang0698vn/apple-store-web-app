@extends('layouts.admin')

@section('title', 'Sản phẩm - Quản trị')
@section('heading', 'Sản phẩm')

@section('content')
    <div class="admin-page-header">
        <p class="text-sm text-gray-600">Quản lý sản phẩm, ảnh và biến thể bán hàng.</p>
        <a href="{{ route('admin.products.create') }}" class="admin-btn-primary shrink-0">Thêm sản phẩm</a>
    </div>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Tên</th>
                    <th class="admin-th">Biến thể</th>
                    <th class="admin-th">Danh mục</th>
                    <th class="admin-th">Dòng</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Dữ liệu</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr>
                        <td class="admin-td">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="font-medium text-blue-700 hover:text-blue-900">{{ $product->name }}</a>
                            <p class="mt-1 text-xs text-gray-500">{{ $product->slug }}</p>
                        </td>
                        <td class="admin-td">
                            <a
                                href="{{ route('admin.products.variants.index', $product->id) }}"
                                class="admin-btn-secondary whitespace-nowrap"
                            >
                                Xem biến thể
                            </a>
                        </td>
                        <td class="admin-td text-gray-600">{{ $product->category?->name }}</td>
                        <td class="admin-td text-gray-600">{{ $product->productSeries?->name ?: '—' }}</td>
                        <td class="admin-td">
                            <div class="space-y-1">
                                <span class="block rounded-full px-2 py-1 text-xs font-medium {{ $product->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'Đang bật' : 'Đã tắt' }}
                                </span>
                                @if ($product->is_featured)
                                    <span class="block rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">Nổi bật</span>
                                @endif
                            </div>
                        </td>
                        <td class="admin-td text-gray-600">{{ $product->variants_count }} biến thể, {{ $product->images_count }} ảnh</td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="admin-btn-secondary">Sửa</a>
                                <form method="post" action="{{ route('admin.products.destroy', $product->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-danger">Ẩn</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">Chưa có sản phẩm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
