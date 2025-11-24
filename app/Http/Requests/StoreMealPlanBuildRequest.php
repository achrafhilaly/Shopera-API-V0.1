<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMealPlanBuildRequest extends FormRequest
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
            'meals' => 'required|array',
            'meals.breakfast' => 'nullable|array',
            'meals.lunch' => 'nullable|array',
            'meals.dinner' => 'nullable|array',
            'meals.breakfast.*' => 'uuid|exists:meals,id',
            'meals.lunch.*' => 'uuid|exists:meals,id',
            'meals.dinner.*' => 'uuid|exists:meals,id',
        ];
    }
}
