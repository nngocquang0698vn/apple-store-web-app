@props([
    'filters',
    'categories',
    'seriesList',
    'colors',
    'storages',
])

@php
    $activeFilters = [];

    if (! empty($filters['q'])) {
        $activeFilters[] = 'Từ khóa: '.$filters['q'];
    }

    if (! empty($filters['category'])) {
        $category = $categories->firstWhere('slug', $filters['category']);
        $activeFilters[] = 'Danh mục: '.($category?->name ?? $filters['category']);
    }

    if (! empty($filters['series'])) {
        $series = $seriesList->firstWhere('slug', $filters['series']);
        $activeFilters[] = 'Dòng: '.($series?->name ?? $filters['series']);
    }

    foreach ((array) ($filters['colors'] ?? []) as $colorSlug) {
        $color = $colors->firstWhere('slug', $colorSlug);
        $activeFilters[] = 'Màu: '.($color?->name ?? $colorSlug);
    }

    foreach ((array) ($filters['storages'] ?? []) as $capacity) {
        $storage = $storages->firstWhere('capacity_gb', (int) $capacity);
        $activeFilters[] = 'Dung lượng: '.($storage?->label ?? $capacity.' GB');
    }

    if (isset($filters['min_price'])) {
        $activeFilters[] = 'Từ '.\App\Support\VndMoney::format((int) $filters['min_price']);
    }

    if (isset($filters['max_price'])) {
        $activeFilters[] = 'Đến '.\App\Support\VndMoney::format((int) $filters['max_price']);
    }

    if (! empty($filters['in_stock'])) {
        $activeFilters[] = 'Còn hàng';
    }

    if (! empty($filters['featured'])) {
        $activeFilters[] = 'Nổi bật';
    }
@endphp

@if ($activeFilters !== [])
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-blue-100 bg-blue-50 px-3 py-2.5 text-sm text-blue-900 lg:px-4 lg:py-3']) }}>
        <p class="text-xs font-medium lg:text-sm">Đang lọc:</p>
        <ul class="mt-1.5 flex flex-wrap gap-1.5 lg:mt-2 lg:gap-2">
            @foreach ($activeFilters as $label)
                <li class="rounded-full bg-white px-2.5 py-0.5 text-xs text-blue-800 lg:px-3 lg:py-1">{{ $label }}</li>
            @endforeach
        </ul>
    </div>
@endif
