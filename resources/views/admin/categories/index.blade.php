@extends('layouts.admin')

@section('title', 'Danh mục - Quản trị')
@section('heading', 'Danh mục')

@section('content')
    <div class="admin-page-header">
        <p class="text-sm text-gray-600">Quản lý nhóm sản phẩm như iPhone, iPad và phụ kiện.</p>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary shrink-0">
            Thêm danh mục
        </a>
    </div>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Tên</th>
                    <th class="admin-th">Slug</th>
                    <th class="admin-th">Thứ tự</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Số mục</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr>
                        <td class="admin-td font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="admin-td text-gray-600">{{ $category->slug }}</td>
                        <td class="admin-td text-gray-600">{{ $category->sort_order }}</td>
                        <td class="admin-td">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $category->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $category->is_active ? 'Đang bật' : 'Đã tắt' }}
                            </span>
                        </td>
                        <td class="admin-td text-gray-600">{{ $category->product_series_count }} dòng, {{ $category->products_count }} sản phẩm</td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn-secondary">Sửa</a>
                                <form method="post" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-danger">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Chưa có danh mục.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
