<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMealPlanRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:meal_plans,name',
            'sku' => 'required|string|max:100|unique:meal_plans,sku',
            'description' => 'required|string|max:2000',
            'image' => 'nullable|string|max:255',
            'breakfast_price_per_day' => 'required|numeric|min:0|max:100',
            'lunch_price_per_day' => 'required|numeric|min:0|max:100',
            'dinner_price_per_day' => 'required|numeric|min:0|max:100',
            'snack_price_per_day' => 'required|numeric|min:0|max:100',
        ];
    }
}
