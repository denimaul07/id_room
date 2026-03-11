<?php

namespace App\Http\Requests\Crm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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
            'telp' => [
                'required',
                'string',
                'max:15',
                Rule::unique('leads', 'notelp')->ignore($this->odata, 'odata')
            ],
            'kodenegara' => 'required|string|max:10',
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('leads', 'email')->ignore($this->odata, 'odata')
            ],
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
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
            'telp.unique' => 'The No Telp has already been taken.',
            'kodenegara.required' => 'The Kode Negara field is required.',
            'email.required' => 'The Email field is required.',
            'email.unique' => 'The Email has already been taken.',
            'tgl_lahir.required' => 'The Tanggal Lahir field is required.',
            'tgl_lahir.date' => 'The Tanggal Lahir field must be a valid date.',
            'jenis_kelamin.required' => 'The Jenis Kelamin field is required.',
            'jenis_kelamin.in' => 'The Jenis Kelamin field must be either Laki-laki or Perempuan.',
            'source.required' => 'The Source field is required.',
            'status.required' => 'The Status field is required.',
            'remaks.required' => 'The Remaks field is required.',
        ];
    }
}
