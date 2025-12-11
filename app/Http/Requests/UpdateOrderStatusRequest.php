<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Status' => 'required|string',
            'Comment' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'Status.required' => 'The Status field is required.',
            'Status.string' => 'The Status must be a string.',
            'Comment.required' => 'The Comment field is required.',
            'Comment.string' => 'The Comment must be a string.',
            'Comment.max' => 'The Comment may not exceed 1000 characters.',
        ];
    }
}

