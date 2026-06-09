<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isActive();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'receiver_name' => ['required', 'string', 'max:100'],
            'receiver_phone' => ['required', 'string', 'max:20', 'regex:/^0\d{9,10}$/'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'address_line' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Vui lòng nhập họ tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'receiver_phone.regex' => 'Số điện thoại phải bắt đầu bằng 0 và có 10 hoặc 11 chữ số.',
            'province.required' => 'Vui lòng nhập tỉnh hoặc thành phố.',
            'district.required' => 'Vui lòng nhập quận hoặc huyện.',
            'ward.required' => 'Vui lòng nhập phường hoặc xã.',
            'address_line.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'note.max' => 'Ghi chú không được vượt quá :max ký tự.',
        ];
    }

    /**
     * @return array{
     *     receiver_name: string,
     *     receiver_phone: string,
     *     province: string,
     *     district: string,
     *     ward: string,
     *     address_line: string,
     *     note: string|null,
     * }
     */
    public function shippingData(): array
    {
        return [
            'receiver_name' => $this->string('receiver_name')->toString(),
            'receiver_phone' => $this->string('receiver_phone')->toString(),
            'province' => $this->string('province')->toString(),
            'district' => $this->string('district')->toString(),
            'ward' => $this->string('ward')->toString(),
            'address_line' => $this->string('address_line')->toString(),
            'note' => $this->filled('note') ? $this->string('note')->toString() : null,
        ];
    }
}
