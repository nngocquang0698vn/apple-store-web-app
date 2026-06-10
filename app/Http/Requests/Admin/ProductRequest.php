<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductSeries;
use App\Support\ProductDescriptionSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'category_id' => $this->filled('category_id') ? (int) $this->input('category_id') : null,
            'product_series_id' => $this->filled('product_series_id') ? (int) $this->input('product_series_id') : null,
            'release_year' => $this->filled('release_year') ? (int) $this->input('release_year') : null,
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
            'description' => ProductDescriptionSanitizer::prepare($this->input('description')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'product_series_id' => ['nullable', 'integer', 'exists:product_series,id'],
            'name' => ['required', 'string', 'max:160'],
            'slug' => [
                'required',
                'string',
                'max:180',
                'alpha_dash',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'release_year' => ['nullable', 'integer', 'min:1976', 'max:'.(date('Y') + 1)],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $seriesId = $this->integer('product_series_id');
            $categoryId = $this->integer('category_id');

            if ($seriesId === 0) {
                return;
            }

            $belongsToCategory = ProductSeries::query()
                ->whereKey($seriesId)
                ->where('category_id', $categoryId)
                ->exists();

            if (! $belongsToCategory) {
                $validator->errors()->add('product_series_id', 'Dòng sản phẩm phải thuộc danh mục đã chọn.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'slug.unique' => 'Slug sản phẩm đã tồn tại.',
        ];
    }
}
