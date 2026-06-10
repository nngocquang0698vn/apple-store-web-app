<?php

namespace App\Http\Requests\Admin;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    OrderStatus::Confirmed->value,
                    OrderStatus::Shipping->value,
                    OrderStatus::Completed->value,
                ]),
            ],
        ];
    }

    public function status(): OrderStatus
    {
        return OrderStatus::from($this->validated('status'));
    }
}
