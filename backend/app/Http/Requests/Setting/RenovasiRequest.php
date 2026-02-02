<?php

namespace App\Http\Requests\Setting;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class RenovasiRequest extends FormRequest
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
            'bannerRenov' => 'nullable|image|mimes:webp|max:2048',
            'colorRenov' => 'required|string',
            'titleRenov' => 'required|string',
            'subTitleRenov' => 'required|string',
            'titleSectionRenov' => 'required|string',
            'descSectionRenov' => 'required|string',
            'urlRenov' => 'required|url',

        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'bannerRenov.image' => 'Banner renovasi harus berupa gambar.',
            'bannerRenov.mimes' => 'Format gambar banner renovasi tidak valid. Hanya diperbolehkan webp.',
            'bannerRenov.max' => 'Ukuran gambar banner renovasi maksimal 2MB.',
            'colorRenov.required' => 'Warna renovasi wajib diisi.',
            'titleRenov.required' => 'Judul renovasi wajib diisi.',
            'subTitleRenov.required' => 'Subjudul renovasi wajib diisi.',
            'titleSectionRenov.required' => 'Judul seksi renovasi wajib diisi.',
            'descSectionRenov.required' => 'Deskripsi seksi renovasi wajib diisi.',
            'urlRenov.required' => 'URL renovasi wajib diisi.',
            'urlRenov.url' => 'Format URL renovasi tidak valid.',
        ];
    }


}
