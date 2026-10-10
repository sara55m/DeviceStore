<?php

namespace App\Http\Requests\Api\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search'   => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'featured' => ['nullable', 'boolean'],
            'sort'     => ['nullable', Rule::in(['newest', 'price_asc', 'price_desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page'     => ['nullable', 'integer', 'min:1'],
        ];
    }
}
