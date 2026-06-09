@props([
    'minPrice',
    'maxPrice' => null,
])

@php
    $minPrice = (int) $minPrice;
    $maxPrice = $maxPrice !== null ? (int) $maxPrice : $minPrice;
    $hasRange = $maxPrice > $minPrice;
@endphp

<p {{ $attributes->merge(['class' => 'text-base font-semibold text-gray-900']) }}>
    @if ($hasRange)
        <span class="text-sm font-normal text-gray-500">Từ</span>
    @endif
    <x-money :amount="$minPrice" />
</p>
