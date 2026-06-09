<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductSeries;
use App\Models\StorageOption;
use App\Queries\ProductQuery;
use App\Support\ProductImageUrl;
use App\Support\ProductVariantSelector;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductFilterRequest $request, ProductQuery $productQuery): View|Response
    {
        $filters = $request->filters();
        $viewData = $this->productIndexViewData($productQuery, $filters);

        if ($request->ajax()) {
            return response()->view('products._results', $viewData);
        }

        return view('products.index', $viewData);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    private function productIndexViewData(ProductQuery $productQuery, array $filters): array
    {
        return [
            'products' => $productQuery->paginate($filters),
            'filters' => $filters,
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'seriesList' => ProductSeries::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'colors' => Color::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'storages' => StorageOption::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ];
    }

    public function show(Request $request, Product $product): View
    {
        $product->load([
            'category',
            'productSeries',
            'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
            'variants' => fn ($query) => $query
                ->where('is_active', true)
                ->with(['color', 'storageOption'])
                ->orderBy('sale_price'),
        ]);

        $selector = new ProductVariantSelector($product);

        if (! $selector->hasVariants()) {
            abort(404);
        }

        $selectedVariant = $selector->resolve(
            $request->query('color'),
            $request->filled('storage') ? $request->integer('storage') : null,
        );

        $selectedColorId = $selectedVariant?->color_id;
        $availableStorages = $selector->storagesForColor($selectedColorId);

        $primaryImage = $product->images->first();
        $primaryImageUrl = $primaryImage
            ? ProductImageUrl::resolve($primaryImage->path)
            : asset('images/placeholders/product-placeholder.svg');

        return view('products.show', [
            'product' => $product,
            'selector' => $selector,
            'selectedVariant' => $selectedVariant,
            'availableStorages' => $availableStorages,
            'primaryImageUrl' => $primaryImageUrl,
            'variantPayload' => $selector->toClientPayload($primaryImageUrl),
        ]);
    }
}
