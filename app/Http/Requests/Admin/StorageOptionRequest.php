<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorageOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'capacity_gb' => $this->filled('capacity_gb') ? (int) $this->input('capacity_gb') : null,
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $storageOption = $this->route('storageOption');

        return [
            'label' => ['required', 'string', 'max:30'],
            'capacity_gb' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('storage_options', 'capacity_gb')->ignore($storageOption?->id),
            ],
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
            'label.required' => 'Vui lòng nhập nhãn dung lượng.',
            'capacity_gb.required' => 'Vui lòng nhập dung lượng GB.',
            'capacity_gb.unique' => 'Dung lượng này đã tồn tại.',
        ];
    }
}
