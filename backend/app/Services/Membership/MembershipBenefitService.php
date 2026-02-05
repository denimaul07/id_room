<?php

namespace App\Services\Membership;
use App\Models\MembershipBenefit;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class MembershipBenefitService
{
    public function list($search = null, $pagging = 10)
    {
        return MembershipBenefit::search($search)->paginate($pagging);
    }

    public function create(array $data)
    {
        $benefit = MembershipBenefit::create([
            'odata'        => (string) Str::uuid(),
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'benefit_type' => $data['benefit_type'] ?? null,
        ]);
        activity()
            ->performedOn($benefit)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created membership benefit');
        return $benefit;
    }

    public function update($odata, array $data)
    {
        $benefit = MembershipBenefit::where('odata', $odata)->first();
        if (!$benefit) {
            throw new HttpResponseException(response()->json(['error' => 'Membership benefit not found'], 404));
        }
        $benefit->update([
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'benefit_type' => $data['benefit_type'] ?? null,
        ]);
        activity()
            ->performedOn($benefit)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated membership benefit');
        return $benefit;
    }

    public function delete($odata)
    {
        $benefit = MembershipBenefit::where('odata', $odata)->first();
        if (!$benefit) {
            throw new HttpResponseException(response()->json(['error' => 'Membership benefit not found'], 404));
        }
        $benefit->delete();
        activity()
            ->performedOn($benefit)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted membership benefit');
        return true;
    }

    public function show($odata)
    {
        $benefit = MembershipBenefit::where('odata', $odata)->first();
        if (!$benefit) {
            throw new HttpResponseException(response()->json(['error' => 'Membership benefit not found'], 404));
        }

        return $benefit->get();
    }
}
