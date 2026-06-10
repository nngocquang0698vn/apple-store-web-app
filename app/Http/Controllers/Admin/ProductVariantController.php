<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductVariantRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(string $product): View
    {
        $product = $this->findProduct($product)->load([
            'variants.color',
            'variants.storageOption',
        ]);

        return view('admin.variants.index', [
            'colors' => $this->colors(),
            'product' => $product,
            'storageOptions' => $this->storageOptions(),
        ]);
    }

    public function store(ProductVariantRequest $request, string $product): RedirectResponse
    {
        $product = $this->findProduct($product);
        $product->variants()->create($request->validated());

        return to_route('admin.products.variants.index', $product->id)
            ->with('success', 'Đã tạo biến thể.');
    }

    public function update(ProductVariantRequest $request, ProductVariant $variant): RedirectResponse
    {
        $variant->update($request->validated());

        return to_route('admin.products.variants.index', $variant->product_id)
            ->with('success', 'Đã cập nhật biến thể.');
    }

    public function destroy(ProductVariant $variant): RedirectResponse
    {
        $variant->update(['is_active' => false]);

        return to_route('admin.products.variants.index', $variant->product_id)
            ->with('success', 'Đã tắt biến thể.');
    }

    private function findProduct(string $product): Product
    {
        return Product::query()->findOrFail($product);
    }

    private function colors()
    {
        return Color::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function storageOptions()
    {
        return StorageOption::query()
            ->orderBy('sort_order')
            ->orderBy('capacity_gb')
            ->get(['id', 'label', 'capacity_gb']);
    }
}
