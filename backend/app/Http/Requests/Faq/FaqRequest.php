<?php

namespace App\Http\Requests\Faq;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class FaqRequest extends FormRequest
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
            'odata_setting' => 'required|string',
            'pertanyaan' => 'required|string',
            'jawaban' => 'required|string',
            'isActive' => 'required|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'odata_setting.required' => 'The OData Setting field is required.',
            'pertanyaan.required' => 'The Question field is required.',
            'jawaban.required' => 'The Answer field is required.',
            'isActive.required' => 'The Active Status field is required.',


        ];
    }


}
