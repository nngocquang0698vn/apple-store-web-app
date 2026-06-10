<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'alpha_dash',
                Rule::unique('categories', 'slug')->ignore($category?->id),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
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
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'slug.unique' => 'Slug danh mục đã tồn tại.',
        ];
    }
}
