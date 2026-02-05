<?php

namespace App\Http\Requests\Membership;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class MembershipBenefitRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'benefit_type' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The Name field is required.',
            'name.string' => 'The Name must be a string.',
            'name.max' => 'The Name may not be greater than 255 characters.',
            'description.string' => 'The Description must be a string.',
            'benefit_type.string' => 'The Benefit Type must be a string.',
            'benefit_type.max' => 'The Benefit Type may not be greater than 100 characters.',
        ];
    }


}
