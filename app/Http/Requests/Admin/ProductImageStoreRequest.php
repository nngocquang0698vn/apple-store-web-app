<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_primary')) {
            $this->merge([
                'is_primary' => $this->boolean('is_primary'),
            ]);
        }

        if ($this->has('sort_order')) {
            $this->merge([
                'sort_order' => (int) $this->input('sort_order'),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'alt_text' => ['nullable', 'string', 'max:180'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Vui lòng chọn ảnh sản phẩm.',
            'image.mimes' => 'Ảnh phải có định dạng JPG, PNG hoặc WebP.',
            'image.max' => 'Ảnh không được vượt quá 4MB.',
        ];
    }
}
