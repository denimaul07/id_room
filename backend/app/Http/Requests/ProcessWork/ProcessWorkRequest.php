<?php

namespace App\Http\Requests\ProcessWork;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProcessWorkRequest extends FormRequest
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
            'title' => 'required|string',
            'desc' => 'required|string',
            'icon' => 'required|string',
            'isActive' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'odata_setting.required' => 'The OData Setting field is required.',
            'title.required' => 'The Title field is required.',
            'desc.required' => 'The Description field is required.',
            'icon.required' => 'The Icon field is required.',
            'isActive.required' => 'The Active Status field is required.',
        ];
    }
}
