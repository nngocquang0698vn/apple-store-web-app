<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->withCount(['productSeries', 'products'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::query()->create($request->validated());

        return to_route('admin.categories.index')
            ->with('success', 'Đã tạo danh mục.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('admin.categories.index')
            ->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->loadCount(['productSeries', 'products']);

        if ($category->product_series_count > 0 || $category->products_count > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có dòng sản phẩm hoặc sản phẩm.');
        }

        $category->delete();

        return to_route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục.');
    }
}
