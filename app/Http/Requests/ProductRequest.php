<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //only admins can create and update products
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('product')?->id;

        return [
            'category_id'      => ['required', 'exists:categories,id'],
            'name.en'          => ['required', 'string', 'max:255'],
            'name.ar'          => ['required', 'string', 'max:255'],
            'description.en'   => ['nullable', 'string', 'max:5000'],
            'description.ar'   => ['nullable', 'string', 'max:5000'],
            'sku'              => ['nullable', 'string', 'max:64', Rule::unique('products', 'sku')->ignore($id)],
            'price'            => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'is_active'        => ['boolean'],
            'is_featured'      => ['boolean'],
            'specs'            => ['nullable', 'array', 'max:30'],
            'specs.*.key'      => ['nullable', 'string', 'max:100'],
            'specs.*.value'    => ['nullable', 'string', 'max:255'],
            'images'           => ['nullable', 'array', 'max:8'],
            'images.*'         => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_images'    => ['nullable', 'array'],
            'remove_images.*'  => ['integer'],
            'primary_image'    => ['nullable', 'integer'],
        ];
    }
}
