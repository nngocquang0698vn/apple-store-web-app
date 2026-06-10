<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const SHOWCASE_LIMIT = 8;

    public function index(): View
    {
        return view('home', [
            'showcaseProducts' => $this->showcaseProducts(),
        ]);
    }

    /**
     * @return Collection<int, Product>
     */
    private function showcaseProducts(): Collection
    {
        $activeVariants = fn (Builder $query): Builder => $query->where('is_active', true);

        return Product::query()
            ->where('is_active', true)
            ->whereHas('images')
            ->whereHas('variants', $activeVariants)
            ->with([
                'productSeries',
                'images' => fn ($imageQuery) => $imageQuery
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->limit(1),
            ])
            ->withMin(['variants' => $activeVariants], 'sale_price')
            ->withMax(['variants' => $activeVariants], 'sale_price')
            ->orderByDesc('is_featured')
            ->orderByDesc('release_year')
            ->limit(self::SHOWCASE_LIMIT)
            ->get();
    }
}
