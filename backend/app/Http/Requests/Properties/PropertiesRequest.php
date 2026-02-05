<?php

namespace App\Http\Requests\Properties;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PropertiesRequest extends FormRequest
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
            'properties' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'province' => 'required|string|max:50',
            'maps' => 'nullable|string|max:50',
            'notelp' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'information' => 'nullable|string',
            'total_rooms' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'image' => 'nullable|string|max:500',
            'isActive' => 'required|integer',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'properties.required' => 'The Property Name field is required.',
            'type.required' => 'The Property Type field is required.',
            'address.required' => 'The Address field is required.',
            'city.required' => 'The City field is required.',
            'province.required' => 'The Province field is required.',
            'total_rooms.required' => 'The Total Rooms field is required.',
            'price_per_night.required' => 'The Price Per Night field is required.',
            'isActive.required' => 'The Active Status field is required.',
        ];
    }
}
