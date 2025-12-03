<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = auth()->user();
        return $user && $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:active,draft,archived',
            'stock_quantity' => 'required|integer|min:0',
            'price.base' => 'required|numeric|min:0',
            'price.discount' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'variants' => 'nullable|array',
            'variants.*.option' => 'required_with:variants|string|max:255',
            'variants.*.value' => 'required_with:variants|string|max:255',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category does not exist.',
            'variants.*.option.required_with' => 'The variant name is required when variants are provided.',
            'variants.*.value.required_with' => 'The variant value is required when variants are provided.',
            'variants.*.price.required_with' => 'The variant price is required when variants are provided.',
            'variants.*.price.min' => 'The variant price must be at least 0.',
            'price.discount.min' => 'The discount price must be at least 0.',
            'price.base.min' => 'The base price must be at least 0.',
            'stock_quantity.min' => 'The stock quantity must be at least 0.',
            'sku.unique' => 'The SKU must be unique.',
            'status.in' => 'The status must be one of the following: active, draft, archived.',
            'name.required' => 'The product name is required.',
            'description.required' => 'The product description is required.',
            'price.base.required' => 'The base price is required.',
            'stock_quantity.required' => 'The stock quantity is required.',
            'category_id.required' => 'The category is required.',
            'sku.required' => 'The SKU is required.',
            'name.max' => 'The product name may not be greater than 255 characters.',
            'sku.max' => 'The SKU may not be greater than 100 characters.',
            'description.max' => 'The product description may not be greater than 1000 characters.',
            'variants.*.option.max' => 'The variant name may not be greater than 255 characters.',
            'variants.*.value.max' => 'The variant value may not be greater than 255 characters.',
            'variants.array' => 'The variants must be an array.',
            'variants.*.option.string' => 'The variant name must be a string.',
            'variants.*.value.string' => 'The variant value must be a string.',
            'price.base.numeric' => 'The base price must be a number.',
            'price.discount.numeric' => 'The discount price must be a number.',
            'stock_quantity.integer' => 'The stock quantity must be an integer.',
        ];
    }
}
