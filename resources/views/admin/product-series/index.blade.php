@extends('layouts.admin')

@section('title', 'Dòng sản phẩm - Quản trị')
@section('heading', 'Dòng sản phẩm')

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Quản lý các dòng như iPhone 16, iPad Air hoặc nhóm phụ kiện.</p>
        <a href="{{ route('admin.product-series.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Thêm dòng sản phẩm
        </a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Danh mục</th>
                    <th class="px-4 py-3">Năm</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3">Sản phẩm</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($seriesItems as $series)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $series->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $series->category?->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $series->release_year ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $series->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $series->is_active ? 'Đang bật' : 'Đã tắt' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $series->products_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.product-series.edit', $series) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50">Sửa</a>
                                <form method="post" action="{{ route('admin.product-series.destroy', $series) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-red-700 hover:bg-red-50">Xóa</button>
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

    <div class="mt-4">{{ $seriesItems->links() }}</div>
@endsection
