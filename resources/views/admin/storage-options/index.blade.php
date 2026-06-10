@extends('layouts.admin')

@section('title', 'Dung lượng - Quản trị')
@section('heading', 'Dung lượng')

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Quản lý các tùy chọn như 128 GB, 256 GB hoặc 1 TB.</p>
        <a href="{{ route('admin.storage-options.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Thêm dung lượng</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Nhãn</th>
                    <th class="px-4 py-3">GB</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3">Biến thể</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($storageOptions as $storageOption)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $storageOption->label }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $storageOption->capacity_gb }}</td>
                        <td class="px-4 py-3">{{ $storageOption->is_active ? 'Đang bật' : 'Đã tắt' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $storageOption->variants_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.storage-options.edit', $storageOption) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Sửa</a>
                                <form method="post" action="{{ route('admin.storage-options.destroy', $storageOption) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-red-700 hover:bg-red-50">Xóa</button>
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

    <div class="mt-4">{{ $storageOptions->links() }}</div>
@endsection
