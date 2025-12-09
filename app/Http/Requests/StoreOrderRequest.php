<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Client Information (Required)
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['required', 'string', 'max:20'],

            // Products Order (Option 1: Multiple products)
            'products' => [
                'required_without:meal_plan_id',
                'prohibited_unless:meal_plan_id,null',
                'nullable',
                'array',
                'min:1',
            ],
            'products.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],

            // Meal Plan Order (Option 2: Single meal plan)
            'meal_plan_id' => [
                'required_without:products',
                'prohibited_unless:products,null',
                'nullable',
                'uuid',
                'exists:meal_plans,id'
            ],
            'days' => [
                'required_with:meal_plan_id',
                'nullable',
                'integer',
                'min:1',
                'max:365'
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Client info messages
            'client_name.required' => 'Client name is required.',
            'client_email.required' => 'Client email is required.',
            'client_email.email' => 'Please provide a valid email address.',
            'client_phone.required' => 'Client phone number is required.',
            
            // Products messages
            'products.required_without' => 'You must provide either products or a meal plan.',
            'products.prohibited_unless' => 'Cannot order both products and a meal plan. Please choose one.',
            'products.min' => 'Please provide at least one product.',
            'products.*.product_id.required' => 'Product ID is required for each product.',
            'products.*.product_id.exists' => 'One or more products do not exist.',
            'products.*.quantity.required' => 'Quantity is required for each product.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
            
            // Meal plan messages
            'meal_plan_id.required_without' => 'You must provide either a meal plan or products.',
            'meal_plan_id.prohibited_unless' => 'Cannot order both a meal plan and products. Please choose one.',
            'meal_plan_id.exists' => 'The selected meal plan does not exist.',
            'days.required_with' => 'Number of days is required for meal plan orders.',
            'days.min' => 'Meal plan duration must be at least 1 day.',
            'days.max' => 'Meal plan duration cannot exceed 365 days.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasProducts = $this->filled('products') && !empty($this->input('products'));
            $hasMealPlan = $this->filled('meal_plan_id');

            // Ensure exactly one order type is provided
            if (!$hasProducts && !$hasMealPlan) {
                $validator->errors()->add(
                    'order_type',
                    'You must provide either products or a meal plan to create an order.'
                );
            }

            // Ensure not both
            if ($hasProducts && $hasMealPlan) {
                $validator->errors()->add(
                    'order_type',
                    'Cannot order both products and a meal plan in the same order. Please choose one.'
                );
            }
        });
    }
}

