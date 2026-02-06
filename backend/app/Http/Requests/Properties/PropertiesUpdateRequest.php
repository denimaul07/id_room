<?php

namespace App\Http\Requests\Properties;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PropertiesUpdateRequest extends FormRequest
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
            'type' => 'required|string|max:100',
            'listing_type' => 'nullable|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'information' => 'nullable|string',
            'price_per_night' => 'required|numeric',
            'price_per_monthly' => 'nullable|numeric',
            'price_per_year' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'total_rooms' => 'required|integer',
            'isActive' => 'required|boolean',
            'images' => 'nullable|image|mimes:webp|max:2048',
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
            'listing_type.required' => 'The Listing Type field is required.',
            'address.required' => 'The Address field is required.',
            'city.required' => 'The City field is required.',
            'province.required' => 'The Province field is required.',
            'latitude.required' => 'The Latitude field is required.',
            'longitude.required' => 'The Longitude field is required.',
            'description.required' => 'The Description field is required.',
            'information.required' => 'The Information field is required.',
            'price_per_night.required' => 'The Price Per Night field is required.',
            'total_rooms.required' => 'The Total Rooms field is required.',
            'isActive.required' => 'The Active Status field is required.',
            'images.image' => 'The Images must be an image file.',
            'images.mimes' => 'The Images must be a file of type: webp.',
            'images.max' => 'The Images may not be greater than 2048 kilobytes.',

        ];
    }
}
