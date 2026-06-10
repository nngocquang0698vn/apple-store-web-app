<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'hex_code' => $this->filled('hex_code') ? strtoupper((string) $this->input('hex_code')) : null,
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $color = $this->route('color');

        return [
            'name' => ['required', 'string', 'max:60'],
            'slug' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('colors', 'slug')->ignore($color?->id),
            ],
            'hex_code' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
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
            'name.required' => 'Vui lòng nhập tên màu.',
            'hex_code.regex' => 'Mã màu phải có dạng #RRGGBB.',
            'slug.unique' => 'Slug màu đã tồn tại.',
        ];
    }
}
