<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ColorController extends Controller
{
    public function index(): View
    {
        return view('admin.colors.index', [
            'colors' => Color::query()
                ->withCount('variants')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.colors.create');
    }

    public function store(ColorRequest $request): RedirectResponse
    {
        Color::query()->create($request->validated());

        return to_route('admin.colors.index')
            ->with('success', 'Đã tạo màu.');
    }

    public function edit(Color $color): View
    {
        return view('admin.colors.edit', [
            'color' => $color,
        ]);
    }

    public function update(ColorRequest $request, Color $color): RedirectResponse
    {
        $color->update($request->validated());

        return to_route('admin.colors.index')
            ->with('success', 'Đã cập nhật màu.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $color->loadCount('variants');

        if ($color->variants_count > 0) {
            return back()->with('error', 'Không thể xóa màu đang được dùng bởi biến thể.');
        }

        $color->delete();

        return to_route('admin.colors.index')
            ->with('success', 'Đã xóa màu.');
    }
}
