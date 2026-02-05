<?php
namespace App\Services\Faq;

use App\Models\Faq;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class FaqService
{
    public function list()
    {
        return Faq::get();
    }

    public function create(array $data)
    {
        $faq = Faq::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'pertanyaan'        => $data['pertanyaan'],
            'jawaban'           => $data['jawaban'],
            'isActive'      => $data['isActive'],
        ]);
        activity()
            ->performedOn($faq)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created FAQ');
        return $faq;
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
        activity()
            ->performedOn($Faq)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated FAQ');
        return $Faq;
    }

    public function delete($odata)
    {
        $Faq = Faq::where('odata', $odata)->first();
        if (!$Faq) {
            throw new HttpResponseException(response()->json(['error' => 'FAQ not found'], 404));
        }
        $Faq->delete();
        activity()
            ->performedOn($Faq)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted FAQ');
        return true;
    }
}