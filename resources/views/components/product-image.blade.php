@props([
    'src' => null,
    'alt' => 'Ảnh sản phẩm',
    'lazy' => true,
])

@php
    $imageUrl = $src ?: asset('images/placeholders/product-placeholder.svg');
@endphp

<div {{ $attributes->merge(['class' => 'aspect-square overflow-hidden rounded-lg bg-gray-100']) }}>
    <img
        src="{{ $imageUrl }}"
        alt="{{ $alt }}"
        class="h-full w-full object-contain"
        @if ($lazy) loading="lazy" @endif
        width="400"
        height="400"
    >
</div>
