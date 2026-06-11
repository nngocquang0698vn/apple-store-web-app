<?php

namespace App\Http\Requests;

use App\Queries\ProductQuery;
use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('in_stock')) {
            $this->merge([
                'in_stock' => filter_var($this->input('in_stock'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }

        if ($this->has('featured')) {
            $this->merge([
                'featured' => filter_var($this->input('featured'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'series' => ['nullable', 'string', 'exists:product_series,slug'],
            'colors' => ['nullable', 'array'],
            'colors.*' => ['string', 'exists:colors,slug'],
            'storages' => ['nullable', 'array'],
            'storages.*' => ['integer', 'exists:storage_options,capacity_gb'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0', 'gte:min_price'],
            'in_stock' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'string', 'max:20'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'in:3,12'],
        ];
    }

    public function perPage(): int
    {
        $requested = $this->integer('per_page');

        if (in_array($requested, [3, 12], true)) {
            return $requested;
        }

        return $this->prefersMobileListing()
            ? ProductQuery::PER_PAGE_MOBILE
            : ProductQuery::PER_PAGE;
    }

    private function prefersMobileListing(): bool
    {
        return preg_match(
            '/android|iphone|ipod|mobile|iemobile|blackberry|opera mini/i',
            strtolower($this->userAgent() ?? ''),
        ) === 1;
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        $filters = $this->validated();

        if (! isset($filters['sort']) || ! in_array($filters['sort'], ProductQuery::SORT_OPTIONS, true)) {
            $filters['sort'] = 'newest';
        }

        $filters['per_page'] = $this->perPage();

        return $filters;
    }
}
