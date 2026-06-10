@extends('layouts.admin')

@section('title', 'Màu sắc - Quản trị')
@section('heading', 'Màu sắc')

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Quản lý màu dùng cho biến thể sản phẩm.</p>
        <a href="{{ route('admin.colors.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Thêm màu</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Mã màu</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3">Biến thể</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($colors as $color)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $color->name }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            @if ($color->hex_code)
                                <span class="inline-flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: {{ $color->hex_code }}"></span>{{ $color->hex_code }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $color->is_active ? 'Đang bật' : 'Đã tắt' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $color->variants_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.colors.edit', $color) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Sửa</a>
                                <form method="post" action="{{ route('admin.colors.destroy', $color) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-red-700 hover:bg-red-50">Xóa</button>
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

    <div class="mt-4">{{ $colors->links() }}</div>
@endsection
