<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductSeriesRequest extends FormRequest
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
            'release_year' => $this->filled('release_year') ? (int) $this->input('release_year') : null,
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $series = $this->route('productSeries');

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:140',
                'alpha_dash',
                Rule::unique('product_series', 'slug')->ignore($series?->id),
            ],
            'release_year' => ['nullable', 'integer', 'min:1976', 'max:'.(date('Y') + 1)],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'name.required' => 'Vui lòng nhập tên dòng sản phẩm.',
            'slug.unique' => 'Slug dòng sản phẩm đã tồn tại.',
        ];
    }
}
