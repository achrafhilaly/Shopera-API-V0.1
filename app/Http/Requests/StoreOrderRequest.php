<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // No preparation needed for products-only orders
    }

    public function rules(): array
    {
        return [
            // Client Information
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],

            // Delivery Address
            'delivery_address' => ['required', 'array'],
            'delivery_address.street' => ['required', 'string', 'max:255'],
            'delivery_address.city' => ['required', 'string', 'max:255'],
            'delivery_address.state' => ['nullable', 'string', 'max:255'],
            'delivery_address.zip_code' => ['required', 'string', 'max:255'],
            'delivery_address.country' => ['required', 'string', 'max:255'],
            'delivery_address.additional_info' => ['nullable', 'string', 'max:500'],

            // Products Order
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_name.required' => 'Contact name is required.',
            'contact_email.required' => 'Contact email is required.',
            'contact_email.email' => 'Please provide a valid email address.',
            'contact_phone.required' => 'Contact phone number is required.',
            
            'delivery_address.street.required' => 'Street address is required.',
            'delivery_address.city.required' => 'City is required.',
            'delivery_address.zip_code.required' => 'Zip code is required.',
            'delivery_address.country.required' => 'Country is required.',
            
            'products.required' => 'Please provide at least one product.',
            'products.min' => 'Please provide at least one product.',
            'products.*.product_id.exists' => 'One or more products do not exist.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasProducts = $this->filled('products') && !empty($this->input('products'));

            // Ensure products are provided
            if (!$hasProducts) {
                $validator->errors()->add('products', 'You must provide at least one product.');
            }
        });
    }
}
