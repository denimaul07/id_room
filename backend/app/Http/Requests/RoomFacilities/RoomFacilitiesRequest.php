<?php

namespace App\Http\Requests\RoomFacilities;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoomFacilitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'room_odata' => 'required|string|max:50',
            'facility_odata' => 'required|string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'room_odata.required' => 'The Room Odata field is required.',
            'facility_odata.required' => 'The Facility Odata field is required.'
        ];
    }
}
