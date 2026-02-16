<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoomsRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'property_odata' => 'required|string|max:50',
            'room_name' => 'required|string|max:100',
            'room_type' => 'required|string|max:100',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'price_month' => 'nullable|numeric',
            'price_year' => 'nullable|numeric',
            'total_room' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status' => 'required|integer|in:0,1'
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'property_odata.required' => 'The Property Odata field is required.',
            'room_name.required' => 'The Room Name field is required.',
            'room_type.required' => 'The Room Type field is required.',
            'capacity.required' => 'The Capacity field is required.',
            'price.required' => 'The Price field is required.',
            'total_room.required' => 'The Total Room field is required.',
            'image.image' => 'The Image must be an image file.',
            'image.mimes' => 'The Image must be a file of type: jpg, png, jpeg, gif, svg.',
            'image.max' => 'The Image may not be greater than 2048 kilobytes.',
            'status.required' => 'The Status field is required.',
            'status.in' => 'The Status must be 0 or 1.'
        ];
    }
}
