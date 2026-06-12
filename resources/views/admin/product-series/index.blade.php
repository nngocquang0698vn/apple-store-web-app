@extends('layouts.admin')

@section('title', 'Dòng sản phẩm - Quản trị')
@section('heading', 'Dòng sản phẩm')

@section('content')
    <div class="admin-page-header">
        <p class="text-sm text-gray-600">Quản lý các dòng như iPhone 16, iPad Air hoặc nhóm phụ kiện.</p>
        <a href="{{ route('admin.product-series.create') }}" class="admin-btn-primary shrink-0">
            Thêm dòng sản phẩm
        </a>
    </div>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Tên</th>
                    <th class="admin-th">Danh mục</th>
                    <th class="admin-th">Năm</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Sản phẩm</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($seriesItems as $series)
                    <tr>
                        <td class="admin-td font-medium text-gray-900">{{ $series->name }}</td>
                        <td class="admin-td text-gray-600">{{ $series->category?->name }}</td>
                        <td class="admin-td text-gray-600">{{ $series->release_year ?: '—' }}</td>
                        <td class="admin-td">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $series->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $series->is_active ? 'Đang bật' : 'Đã tắt' }}
                            </span>
                        </td>
                        <td class="admin-td text-gray-600">{{ $series->products_count }}</td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.product-series.edit', $series) }}" class="admin-btn-secondary">Sửa</a>
                                <form method="post" action="{{ route('admin.product-series.destroy', $series) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Chưa có dòng sản phẩm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $seriesItems->links() }}</div>
@endsection
