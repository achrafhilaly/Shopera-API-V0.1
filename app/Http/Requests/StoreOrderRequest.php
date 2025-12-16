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
        if ($this->has('menu_selections')) {
            // Convert menu_selections object to array for validation
            $menuArray = [];
            
            foreach ($this->input('menu_selections', []) as $date => $meals) {
                $menuArray[] = [
                    'date' => $date,
                    'breakfast' => $meals['breakfast'] ?? null,
                    'lunch' => $meals['lunch'] ?? null,
                    'dinner' => $meals['dinner'] ?? null,
                ];
            }
            
            $this->merge([
                'menu_selections_array' => $menuArray,
            ]);
        }
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
            'products' => ['nullable', 'array', 'min:1'],
            'products.*.product_id' => ['required_with:products', 'uuid', 'exists:products,id'],
            'products.*.quantity' => ['required_with:products', 'integer', 'min:1'],

            // Meal Plan Order
            'meal_plan_id' => ['nullable', 'uuid', 'exists:meal_plans,id'],
            'total_price' => ['required_with:meal_plan_id', 'numeric', 'min:0'],
            
            // Preferences
            'preferences' => ['required_with:meal_plan_id', 'array'],
            'preferences.meals' => ['required_with:meal_plan_id', 'array', 'min:1'],
            'preferences.meals.*' => ['required', 'string', 'in:breakfast,lunch,dinner'],
            'preferences.snacks_per_day' => ['required_with:meal_plan_id', 'integer', 'min:0', 'max:5'],
            'preferences.duration_weeks' => ['required_with:meal_plan_id', 'integer', 'min:1', 'max:52'],

            // Delivery Days (now simple Y-m-d format)
            'delivery_days' => ['required_with:meal_plan_id', 'array', 'min:3'],
            'delivery_days.*' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],

            // Menu Selections (transformed array)
            'menu_selections_array' => ['required_with:meal_plan_id', 'array'],
            'menu_selections_array.*.date' => ['required', 'date', 'date_format:Y-m-d'],
            'menu_selections_array.*.breakfast' => ['nullable', 'uuid'],
            'menu_selections_array.*.lunch' => ['nullable', 'uuid'],
            'menu_selections_array.*.dinner' => ['nullable', 'uuid'],
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
            
            'products.min' => 'Please provide at least one product.',
            'products.*.product_id.exists' => 'One or more products do not exist.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
            
            'meal_plan_id.exists' => 'The selected meal plan does not exist.',
            
            'preferences.required_with' => 'Preferences are required for meal plan orders.',
            'preferences.meals.*.in' => 'Invalid meal type. Must be breakfast, lunch, or dinner.',
            'preferences.snacks_per_day.min' => 'Snacks per day cannot be negative.',
            'preferences.snacks_per_day.max' => 'Snacks per day cannot exceed 5.',
            'preferences.duration_weeks.min' => 'Duration must be at least 1 week.',
            'preferences.duration_weeks.max' => 'Duration cannot exceed 52 weeks.',
            
            'delivery_days.required_with' => 'Delivery days are required for meal plan orders.',
            'delivery_days.min' => 'Please provide at least 3 delivery days.',
            'delivery_days.*.date_format' => 'Delivery days must be in Y-m-d format.',
            'delivery_days.*.after_or_equal' => 'Delivery days cannot be in the past.',
            
            'menu_selections_array.*.breakfast.uuid' => 'Invalid breakfast product ID format.',
            'menu_selections_array.*.lunch.uuid' => 'Invalid lunch product ID format.',
            'menu_selections_array.*.dinner.uuid' => 'Invalid dinner product ID format.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasProducts = $this->filled('products') && !empty($this->input('products'));
            $hasMealPlan = $this->filled('meal_plan_id');

            // Ensure exactly one order type
            if (!$hasProducts && !$hasMealPlan) {
                $validator->errors()->add('order_type', 'You must provide either products or a meal plan.');
            }

            if ($hasProducts && $hasMealPlan) {
                $validator->errors()->add('order_type', 'Cannot order both products and a meal plan.');
            }

            // Validate menu selections match delivery days
            if ($hasMealPlan && $this->filled('menu_selections_array') && $this->filled('delivery_days')) {
                $deliveryDays = $this->input('delivery_days');
                $menuDates = collect($this->input('menu_selections_array'))->pluck('date')->toArray();
                
                $missingDates = array_diff($deliveryDays, $menuDates);
                if (!empty($missingDates)) {
                    foreach ($missingDates as $date) {
                        $validator->errors()->add('menu_selections', "Missing menu selection for delivery day: {$date}");
                    }
                }
            }

            // Validate required meals are selected for each day
            if ($hasMealPlan && $this->filled('preferences.meals') && $this->filled('menu_selections_array')) {
                $requiredMeals = $this->input('preferences.meals');
                
                foreach ($this->input('menu_selections_array') as $index => $daySelection) {
                    $date = $daySelection['date'];
                    
                    foreach ($requiredMeals as $mealType) {
                        if (empty($daySelection[$mealType])) {
                            $validator->errors()->add(
                                "menu_selections.{$date}.{$mealType}",
                                "You must select a {$mealType} for {$date}."
                            );
                        }
                    }
                }
            }

            // Validate meal IDs exist in database
            if ($hasMealPlan && $this->filled('menu_selections_array')) {
                $mealIds = [];
                
                foreach ($this->input('menu_selections_array') as $selection) {
                    if (!empty($selection['breakfast'])) $mealIds[] = $selection['breakfast'];
                    if (!empty($selection['lunch'])) $mealIds[] = $selection['lunch'];
                    if (!empty($selection['dinner'])) $mealIds[] = $selection['dinner'];
                }
                
                $mealIds = array_unique($mealIds);
                $existingIds = DB::table('meals')->whereIn('id', $mealIds)->pluck('id')->toArray();
                $missingIds = array_diff($mealIds, $existingIds);
                
                if (!empty($missingIds)) {
                    foreach ($missingIds as $missingId) {
                        $validator->errors()->add('menu_selections', "meal with ID {$missingId} does not exist.");
                    }
                }
            }
        });
    }
}
