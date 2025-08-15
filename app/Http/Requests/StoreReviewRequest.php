<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'food_product_id' => 'required|exists:food_products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10',
            'images' => 'nullable|array|max:5',
            'images.*' => 'string|url',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'food_product_id.required' => 'Food product is required.',
            'food_product_id.exists' => 'The selected food product is invalid.',
            'rating.required' => 'Please provide a rating.',
            'rating.integer' => 'Rating must be a number.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'content.required' => 'Please write a review.',
            'content.min' => 'Review must be at least 10 characters long.',
            'images.max' => 'You can upload maximum 5 images.',
            'images.*.url' => 'Each image must be a valid URL.',
        ];
    }
}