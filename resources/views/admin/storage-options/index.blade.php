@extends('layouts.admin')

@section('title', 'Dung lượng - Quản trị')
@section('heading', 'Dung lượng')

@section('content')
    <div class="admin-page-header">
        <p class="text-sm text-gray-600">Quản lý các tùy chọn như 128 GB, 256 GB hoặc 1 TB.</p>
        <a href="{{ route('admin.storage-options.create') }}" class="admin-btn-primary shrink-0">Thêm dung lượng</a>
    </div>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Nhãn</th>
                    <th class="admin-th">GB</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Biến thể</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($storageOptions as $storageOption)
                    <tr>
                        <td class="admin-td font-medium text-gray-900">{{ $storageOption->label }}</td>
                        <td class="admin-td text-gray-600">{{ $storageOption->capacity_gb }}</td>
                        <td class="admin-td">{{ $storageOption->is_active ? 'Đang bật' : 'Đã tắt' }}</td>
                        <td class="admin-td text-gray-600">{{ $storageOption->variants_count }}</td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.storage-options.edit', $storageOption) }}" class="admin-btn-secondary">Sửa</a>
                                <form method="post" action="{{ route('admin.storage-options.destroy', $storageOption) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Chưa có dung lượng.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $storageOptions->links() }}</div>
@endsection
