<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoomSubRoomRequest extends FormRequest
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
            'odata_room' => 'required|string|max:50',
            'name_room' => 'required|string|max:100',
            'code_room' => 'required|string|max:50',
            'type_bad' => 'required|string|max:50',
            'price' => 'required|numeric',
            'include_breakfast' => 'required|in:Y,N',
            'smoking_area' => 'required|in:Y,N',
            'status' => 'required|integer|in:0,1'
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'odata_room.required' => 'The Odata Room field is required.',
            'name_room.required' => 'The Name Room field is required.',
            'code_room.required' => 'The Code Room field is required.',
            'type_bad.required' => 'The Type Bad field is required.',
            'price.required' => 'The Price field is required.',
            'include_breakfast.required' => 'The Include Breakfast field is required.',
            'include_breakfast.in' => 'The Include Breakfast field must be Y or N.',
            'smoking_area.required' => 'The Smoking Area field is required.',
            'smoking_area.in' => 'The Smoking Area field must be Y or N.',
            'status.required' => 'The Status field is required.',
            'status.in' => 'The Status must be 0 or 1.'
        ];
    }
}
