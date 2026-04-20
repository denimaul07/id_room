<?php

namespace App\Http\Requests\Campaigns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('campaign', 'name')->ignore($this->odata, 'odata')
            ],
            'template_name' => 'required|string|max:255',

            'schedule_time' => [
                'nullable',
                Rule::requiredIf($this->status === 'scheduled'),
            ],

            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'status' => 'required'
        ];
    }
    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'template_name.required' => 'The template name field is required.',
            'schedule_time.date' => 'The schedule time must be a valid date.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg.',
            'images.*.max' => 'Each image must not exceed 2048 kilobytes.',
            'status.required' => 'The status field is required.'
        ];
    }
}
