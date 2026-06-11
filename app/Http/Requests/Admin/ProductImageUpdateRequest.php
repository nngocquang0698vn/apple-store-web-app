<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageUpdateRequest extends FormRequest
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
            'alt_text' => ['nullable', 'string', 'max:180'],
            'sort_order' => ['sometimes', 'required', 'integer', 'min:0'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}
