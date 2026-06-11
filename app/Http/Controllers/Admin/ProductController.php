<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSeries;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()
                ->with(['category', 'productSeries'])
                ->withCount(['images', 'variants'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create($request->validated());

        return to_route('admin.products.show', $product->id)
            ->with('success', 'Đã tạo sản phẩm.');
    }

    public function show(string $product): View
    {
        return view('admin.products.show', [
            'product' => $this->findProduct($product)->load([
                'category',
                'productSeries',
                'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
                'variants.color',
                'variants.storageOption',
            ]),
        ]);
    }

    public function edit(string $product): View
    {
        return view('admin.products.edit', [
            ...$this->formData(),
            'product' => $this->findProduct($product),
        ]);
    }

    public function update(ProductRequest $request, string $product): RedirectResponse
    {
        $product = $this->findProduct($product);
        $product->update($request->validated());

        return to_route('admin.products.show', $product->id)
            ->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(string $product): RedirectResponse
    {
        $this->findProduct($product)->delete();

        return to_route('admin.products.index')
            ->with('success', 'Đã ẩn sản phẩm.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'categories' => Category::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name']),
            'seriesItems' => ProductSeries::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'category_id', 'name']),
        ];
    }

    private function findProduct(string $product): Product
    {
        return Product::query()->findOrFail($product);
    }
}
