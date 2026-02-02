<?php

namespace App\Http\Requests\Mitra;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class MitraUpdateRequest extends FormRequest
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
            'nama' => 'required|string',
            'image' => 'sometimes|image|mimes:webp|max:1024',
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
            'nama.required' => 'The Name field is required.',
            'image.image' => 'The Image must be an image file.',
            'image.mimes' => 'The Image must be a file of type: webp.',
            'image.max' => 'The Image may not be greater than 1024 kilobytes.',
            'isActive.required' => 'The Active Status field is required.',


        ];
    }


}
