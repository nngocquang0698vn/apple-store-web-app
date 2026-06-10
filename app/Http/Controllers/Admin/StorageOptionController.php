<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorageOptionRequest;
use App\Models\StorageOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StorageOptionController extends Controller
{
    public function index(): View
    {
        return view('admin.storage-options.index', [
            'storageOptions' => StorageOption::query()
                ->withCount('variants')
                ->orderBy('sort_order')
                ->orderBy('capacity_gb')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.storage-options.create');
    }

    public function store(StorageOptionRequest $request): RedirectResponse
    {
        StorageOption::query()->create($request->validated());

        return to_route('admin.storage-options.index')
            ->with('success', 'Đã tạo dung lượng.');
    }

    public function edit(StorageOption $storageOption): View
    {
        return view('admin.storage-options.edit', [
            'storageOption' => $storageOption,
        ]);
    }

    public function update(StorageOptionRequest $request, StorageOption $storageOption): RedirectResponse
    {
        $storageOption->update($request->validated());

        return to_route('admin.storage-options.index')
            ->with('success', 'Đã cập nhật dung lượng.');
    }

    public function destroy(StorageOption $storageOption): RedirectResponse
    {
        $storageOption->loadCount('variants');

        if ($storageOption->variants_count > 0) {
            return back()->with('error', 'Không thể xóa dung lượng đang được dùng bởi biến thể.');
        }

        $storageOption->delete();

        return to_route('admin.storage-options.index')
            ->with('success', 'Đã xóa dung lượng.');
    }
}
