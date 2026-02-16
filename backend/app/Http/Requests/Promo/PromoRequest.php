<?php

namespace App\Http\Requests\Promo;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
            'data' => $validator->errors()
            ], 400)
        );
    }


    public function rules() : array
    {
        return [
            'banner' => 'required|image|mimes:webp|max:2048',
            'isActive' => 'required'
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'banner.required' => 'The Banner field is required.',
            'banner.image' => 'The Banner must be an image file.',
            'banner.mimes' => 'The Banner must be a file of type: webp.',
            'banner.max' => 'The Banner may not be greater than 2048 kilobytes.',
            'isActive.required' => 'The Active Status field is required.',
        ];
    }
}
