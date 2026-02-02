<?php
namespace App\Services\Faq;

use App\Models\Faq;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class FaqService
{
    public function list()
    {
        return Faq::get();
    }

    public function create(array $data)
    {
        return Faq::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'pertanyaan'        => $data['pertanyaan'],
            'jawaban'           => $data['jawaban'],
            'isActive'      => $data['isActive'],
        ]);
    }

    public function update($odata, array $data)
    {
        $Faq = Faq::where('odata', $odata)->first();
        if (!$Faq) {
            throw new HttpResponseException(response()->json(['error' => 'FAQ not found'], 404));
        }

        $Faq->pertanyaan = $data['pertanyaan'];
        $Faq->jawaban = $data['jawaban'];
        $Faq->isActive = $data['isActive'];

        $Faq->save();

        return $Faq;
    }

    public function delete($odata)
    {
        $Faq = Faq::where('odata', $odata)->first();
        if (!$Faq) {
            throw new HttpResponseException(response()->json(['error' => 'FAQ not found'], 404));
        }

        return $Faq->delete();
    }
}