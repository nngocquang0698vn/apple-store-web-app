@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => '',
    'rows' => 6,
])

@php
    $fieldId = $id ?? $name;
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if ($label)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        data-rich-text-editor
        data-upload-url="{{ route('admin.products.description-images.store', absolute: false) }}"
        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
    >{{ $value }}</textarea>

    <p class="text-xs text-gray-500">
        Hỗ trợ định dạng văn bản, chèn ảnh (tải lên) và video YouTube. Nếu JavaScript tắt, vẫn có thể nhập văn bản thuần.
    </p>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
