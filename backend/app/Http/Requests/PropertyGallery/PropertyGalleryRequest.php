<?php

namespace App\Http\Requests\PropertyGallery;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PropertyGalleryRequest extends FormRequest
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
            'property_odata' => 'required|string|max:50',
            'image' => 'required|image|mimes:webp,jpeg,jpg,png|max:4096'
        ];
    }

    public function messages(): array
    {
        return [
            'property_odata.required' => 'The Property Odata field is required.',
            'image.required' => 'The Image field is required.',
            'image.image' => 'The Image must be an image file.',
            'image.mimes' => 'The Image must be a file of type: webp, jpeg, jpg, png.',
            'image.max' => 'The Image may not be greater than 4096 kilobytes.'
        ];
    }
}
