<?php

namespace App\Http\Requests\Testimoni;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestimoniRequest extends FormRequest
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
            'odata_setting' => 'required|string',
            'nama' => 'required|string',
            'location' => 'required|string',
            'image' => 'required|image|mimes:webp|max:1024',
            'rate' => 'required|integer',
            'type' => 'required',
            'desc' => 'required|string',
            'isActive' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'odata_setting.required' => 'OData Setting wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'location.required' => 'Lokasi wajib diisi.',
            'rate.required' => 'Rate wajib diisi.',
            'desc.required' => 'Deskripsi wajib diisi.',
            'isActive.required' => 'Status aktif wajib diisi.',
            'type.required' => 'Tipe wajib diisi.',
            'image.required' => 'Gambar wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat webp.',
            'image.max' => 'Ukuran gambar maksimal 1024 kilobyte.',
        ];
    }
}
