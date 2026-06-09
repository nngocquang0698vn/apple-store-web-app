<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductSeries;
use App\Models\StorageOption;
use App\Queries\ProductQuery;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductFilterRequest $request, ProductQuery $productQuery): View
    {
        $filters = $request->filters();

        return view('products.index', [
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
        ]);
    }

    public function show(Product $product): Response
    {
        abort(404);
    }
}
