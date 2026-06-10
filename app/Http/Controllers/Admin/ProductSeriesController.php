<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductSeriesRequest;
use App\Models\Category;
use App\Models\ProductSeries;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductSeriesController extends Controller
{
    public function index(): View
    {
        return view('admin.product-series.index', [
            'seriesItems' => ProductSeries::query()
                ->with('category')
                ->withCount('products')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.product-series.create', [
            'categories' => $this->categories(),
        ]);
    }

    public function store(ProductSeriesRequest $request): RedirectResponse
    {
        ProductSeries::query()->create($request->validated());

        return to_route('admin.product-series.index')
            ->with('success', 'Đã tạo dòng sản phẩm.');
    }

    public function edit(ProductSeries $productSeries): View
    {
        return view('admin.product-series.edit', [
            'categories' => $this->categories(),
            'series' => $productSeries,
        ]);
    }

    public function update(ProductSeriesRequest $request, ProductSeries $productSeries): RedirectResponse
    {
        $productSeries->update($request->validated());

        return to_route('admin.product-series.index')
            ->with('success', 'Đã cập nhật dòng sản phẩm.');
    }

    public function destroy(ProductSeries $productSeries): RedirectResponse
    {
        $productSeries->loadCount('products');

        if ($productSeries->products_count > 0) {
            return back()->with('error', 'Không thể xóa dòng sản phẩm đang có sản phẩm.');
        }

        $productSeries->delete();

        return to_route('admin.product-series.index')
            ->with('success', 'Đã xóa dòng sản phẩm.');
    }

    private function categories()
    {
        return Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
