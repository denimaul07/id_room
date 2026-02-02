<?php

namespace App\Http\Requests\Setting;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pembuatan instruksi bayar dengan PO
 */
class SettingRequest extends FormRequest
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
            'siteName' => 'required|string',
            'primaryColor' => 'required|string',
            'primaryColorHover' => 'required|string',
            'primaryTextColor' => 'required|string',
            'secondColor' => 'required|string',
            'secondColorHover' => 'required|string',
            'secondTextColor' => 'required|string',
            'logo' => 'max:512|mimes:webp',
            'favicon' => 'max:512|mimes:ico',
            'imageBanner' => 'max:512|mimes:webp',
            'titleBanner' => 'required|string',
            'subTitleBanner' => 'required|string',
            'colorTitleBanner' => 'required|string',
            'navBarColor' => 'required|string',
            'navBarTextColor' => 'required|string',
            'footerDesk' => 'required|string',
            'footerColor' => 'required|string',
            'footerTextColor' => 'required|string',
            'syaratketentuan' => 'required|string',
            'privacypolicy' => 'required|string',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'siteName.required' => 'Site Name Tidak Boleh Kosong',
            'primaryColor.required' => 'Primary Color Tidak Boleh Kosong',
            'primaryColorHover.required' => 'Primary Color Hover Tidak Boleh Kosong',
            'primaryTextColor.required' => 'Primary Text Color Tidak Boleh Kosong',
            'secondColor.required' => 'Secondary Color Tidak Boleh Kosong',
            'secondColorHover.required' => 'Secondary Color Hover Tidak Boleh Kosong',
            'secondTextColor.required' => 'Secondary Text Color Tidak Boleh Kosong',
            'logo.required' => 'Logo Tidak Boleh Kosong',
            'logo.max' => 'Ukuran Logo Maksimal 512 KB',
            'logo.mimes' => 'Format Logo Harus Berupa webp',
            'favicon.required' => 'Favicon Tidak Boleh Kosong',
            'imageBanner.required' => 'Image Banner Tidak Boleh Kosong',
            'imageBanner.max' => 'Ukuran Image Banner Maksimal 512 KB',
            'imageBanner.mimes' => 'Format Image Banner Harus Berupa webp',
            'titleBanner.required' => 'Title Banner Tidak Boleh Kosong',
            'subTitleBanner.required' => 'Sub Title Banner Tidak Boleh Kosong',
            'colorTitleBanner.required' => 'Color Title Banner Tidak Boleh Kosong',
            'navBarColor.required' => 'Nav Bar Color Tidak Boleh Kosong',
            'navBarTextColor.required' => 'Nav Bar Text Color Tidak Boleh Kosong',
            'footerDesk.required' => 'Footer Description Tidak Boleh Kosong',
            'footerColor.required' => 'Footer Color Tidak Boleh Kosong',
            'footerTextColor.required' => 'Footer Text Color Tidak Boleh Kosong',
            'syaratketentuan.required' => 'Syarat dan Ketentuan Tidak Boleh Kosong',
            'privacypolicy.required' => 'Privacy Policy Tidak Boleh Kosong',
        ];
    }


}
