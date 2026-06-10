<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'color_id' => $this->filled('color_id') ? (int) $this->input('color_id') : null,
            'storage_option_id' => $this->filled('storage_option_id') ? (int) $this->input('storage_option_id') : null,
            'original_price' => $this->filled('original_price') ? (int) $this->input('original_price') : null,
            'sale_price' => $this->filled('sale_price') ? (int) $this->input('sale_price') : null,
            'stock_quantity' => $this->filled('stock_quantity') ? (int) $this->input('stock_quantity') : null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $variant = $this->route('variant');

        return [
            'color_id' => ['nullable', 'integer', 'exists:colors,id'],
            'storage_option_id' => ['nullable', 'integer', 'exists:storage_options,id'],
            'sku' => [
                'required',
                'string',
                'max:60',
                Rule::unique('product_variants', 'sku')->ignore($variant?->id),
            ],
            'original_price' => ['nullable', 'integer', 'min:0'],
            'sale_price' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $originalPrice = $this->input('original_price');
            $salePrice = $this->integer('sale_price');

            if (! $validator->errors()->has('sale_price') && $originalPrice !== null && $salePrice > (int) $originalPrice) {
                $validator->errors()->add('sale_price', 'Giá bán không được lớn hơn giá niêm yết.');
            }

            if ($validator->errors()->has('color_id') || $validator->errors()->has('storage_option_id')) {
                return;
            }

            $productId = $this->productId();
            $variant = $this->route('variant');

            $duplicate = ProductVariant::query()
                ->where('product_id', $productId)
                ->when(
                    $this->input('color_id') === null,
                    fn ($query) => $query->whereNull('color_id'),
                    fn ($query) => $query->where('color_id', $this->integer('color_id')),
                )
                ->when(
                    $this->input('storage_option_id') === null,
                    fn ($query) => $query->whereNull('storage_option_id'),
                    fn ($query) => $query->where('storage_option_id', $this->integer('storage_option_id')),
                )
                ->when($variant !== null, fn ($query) => $query->where('id', '!=', $variant->id))
                ->exists();

            if ($duplicate) {
                $validator->errors()->add('color_id', 'Tổ hợp màu và dung lượng đã tồn tại cho sản phẩm này.');
            }
        });
    }

    private function productId(): int
    {
        $variant = $this->route('variant');

        if ($variant instanceof ProductVariant) {
            return (int) $variant->product_id;
        }

        return (int) $this->route('product');
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'sale_price.required' => 'Vui lòng nhập giá bán.',
            'stock_quantity.required' => 'Vui lòng nhập tồn kho.',
        ];
    }
}
