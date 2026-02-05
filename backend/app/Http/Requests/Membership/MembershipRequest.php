<?php

namespace App\Http\Requests\Membership;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class MembershipRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'desc' => 'nullable|string',
            'price' => 'required|integer',
            'duration' => 'required|integer',
            'isActive' => 'required|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The Title field is required.',
            'desc.required' => 'The Description field is required.',
            'price.required' => 'The Price field is required.',
            'duration.required' => 'The Duration field is required.',
            'isActive.required' => 'The Active Status field is required.',
        ];
    }


}
