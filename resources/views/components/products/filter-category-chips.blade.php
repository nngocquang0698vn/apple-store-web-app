@props([
    'filters',
    'categories',
])

@php
    $baseQuery = request()->except(['page', 'category']);
    $activeCategory = $filters['category'] ?? '';
@endphp

<nav
    {{ $attributes->merge(['class' => 'flex gap-2 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden']) }}
    aria-label="Lọc danh mục nhanh"
>
    <a
        href="{{ route('products.index', $baseQuery) }}"
        data-filter-category-chip
        @class([
            'shrink-0 whitespace-nowrap rounded-full border px-3 py-1.5 text-sm font-medium transition duration-200',
            'border-blue-600 bg-blue-50 text-blue-700' => $activeCategory === '',
            'border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:text-blue-600' => $activeCategory !== '',
        ])
        aria-current="{{ $activeCategory === '' ? 'true' : 'false' }}"
    >
        Tất cả
    </a>
    @foreach ($categories as $category)
        @php
            $isActive = $activeCategory === $category->slug;
            $chipQuery = array_merge($baseQuery, ['category' => $category->slug]);
        @endphp
        <a
            href="{{ route('products.index', $chipQuery) }}"
            data-filter-category-chip
            @class([
                'shrink-0 whitespace-nowrap rounded-full border px-3 py-1.5 text-sm font-medium transition duration-200',
                'border-blue-600 bg-blue-50 text-blue-700' => $isActive,
                'border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:text-blue-600' => ! $isActive,
            ])
            aria-current="{{ $isActive ? 'true' : 'false' }}"
        >
            {{ $category->name }}
        </a>
    @endforeach
</nav>
