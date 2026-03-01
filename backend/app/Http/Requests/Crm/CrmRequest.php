<?php

namespace App\Http\Requests\Crm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CrmRequest extends FormRequest
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
            'telp' => 'required|string|max:15',
            'kodenegara' => 'required|string|max:10',
            'email' => 'required|email|max:50',
            'source' => 'required|string|max:50',
            'remaks' => 'required|string|max:255',
            'status' => 'sometimes|max:50',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The Name field is required.',
            'telp.required' => 'The No Telp field is required.',
            'kodenegara.required' => 'The Kode Negara field is required.',
            'email.required' => 'The Email field is required.',
            'source.required' => 'The Source field is required.',
            'status.required' => 'The Status field is required.',
            'remaks.required' => 'The Remaks field is required.',
        ];
    }
}
