<?php

namespace App\Http\Requests\PropertyFacilities;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PropertyFacilitiesUpdateRequest extends FormRequest
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
            'property_odata' => 'required|string|max:50',
            'facility_odata' => 'required|string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'property_odata.required' => 'The Property Odata field is required.',
            'facility_odata.required' => 'The Facility Odata field is required.'
        ];
    }
}
