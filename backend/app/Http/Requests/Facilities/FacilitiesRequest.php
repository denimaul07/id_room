<?php

namespace App\Http\Requests\Facilities;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FacilitiesRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'type' => 'required|array',
            'type.*' => 'string|max:50',
            'icon' => 'required|string|max:50'
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The Name field is required.',
            'type.required' => 'The Type field is required.',
            'icon.required' => 'The Icon field is required.'
        ];
    }
}
