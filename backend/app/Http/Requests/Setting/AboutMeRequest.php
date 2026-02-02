<?php

namespace App\Http\Requests\Setting;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class AboutMeRequest extends FormRequest
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
            'aboutme' => 'required|string',
            'colorAboutMe' => 'required|string',
            'visi' => 'required|string',
            'misi' => 'required|string',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'aboutme.required' => 'About Me wajib diisi.',
            'colorAboutMe.required' => 'Warna About Me wajib diisi.',
            'visi.required' => 'Visi wajib diisi.',
            'misi.required' => 'Misi wajib diisi.',
        ];
    }


}
