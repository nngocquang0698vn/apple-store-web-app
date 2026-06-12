@extends('layouts.admin')

@section('title', 'Màu sắc - Quản trị')
@section('heading', 'Màu sắc')

@section('content')
    <div class="admin-page-header">
        <p class="text-sm text-gray-600">Quản lý màu dùng cho biến thể sản phẩm.</p>
        <a href="{{ route('admin.colors.create') }}" class="admin-btn-primary shrink-0">Thêm màu</a>
    </div>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Tên</th>
                    <th class="admin-th">Mã màu</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Biến thể</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($colors as $color)
                    <tr>
                        <td class="admin-td font-medium text-gray-900">{{ $color->name }}</td>
                        <td class="admin-td text-gray-600">
                            @if ($color->hex_code)
                                <span class="inline-flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: {{ $color->hex_code }}"></span>{{ $color->hex_code }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="admin-td">{{ $color->is_active ? 'Đang bật' : 'Đã tắt' }}</td>
                        <td class="admin-td text-gray-600">{{ $color->variants_count }}</td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.colors.edit', $color) }}" class="admin-btn-secondary">Sửa</a>
                                <form method="post" action="{{ route('admin.colors.destroy', $color) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Chưa có màu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $colors->links() }}</div>
@endsection
