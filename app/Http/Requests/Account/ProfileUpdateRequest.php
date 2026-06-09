<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^0\d{9,10}$/',
                Rule::unique('users', 'phone')->ignore($this->user()),
            ],
            'default_address' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá :max ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 0 và có 10 hoặc 11 chữ số.',
            'default_address.max' => 'Địa chỉ không được vượt quá :max ký tự.',
        ];
    }
}
