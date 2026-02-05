<?php
namespace App\Services\Membership;

use App\Models\Membership;
use App\Models\MembershipPlanBenefit;
use App\Models\MembershipBenefit;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class MembershipService
{
    public function list($search = null, $pagging = 10)
    {
        return Membership::with('benefits','benefits.benefitDetails')->search($search)->paginate($pagging);
    }

    public function create(array $data)
    {
        $membership = Membership::create([
            'odata'    => (string) Str::uuid(),
            'odata_setting' => 'd73e5120-5b9c-44c1-9154-b718794e8fc3',
            'title'    => $data['title'],
            'desc'     => $data['desc'],
            'price'    => $data['price'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'fee_admin' => $data['fee_admin'] ?? 0,
            'cancel_booking_fee' => $data['cancel_booking_fee'] ?? 0,
            'duration' => $data['duration'],
            'isActive' => $data['isActive'],
        ]);

        // Simpan benefit jika ada
        if (!empty($data['benefits']) && is_array($data['benefits'])) {
            foreach ($data['benefits'] as $benefit) {
                $benefits = MembershipBenefit::whereIn('odata', [$benefit['odata_benefit']])->first();
                MembershipPlanBenefit::create([
                    'odata' => (string) Str::uuid(),
                    'odata_membership' => $membership->odata,
                    'id_membership' => $membership->id,
                    'odata_benefit' => $benefit['odata_benefit'],
                    'id_benefit' => $benefits->id,
                    'value' => $benefit['value'],
                ]);
            }
        }
        activity()
            ->performedOn($membership)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created membership');
        return $membership;
    }

    public function update($odata, array $data)
    {
        $membership = Membership::where('odata', $odata)->first();
        if (!$membership) {
            throw new HttpResponseException(response()->json(['error' => 'Membership not found'], 404));
        }
        $membership->update([
            'title'    => $data['title'],
            'desc'     => $data['desc'],
            'price'    => $data['price'],
            'duration' => $data['duration'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'fee_admin' => $data['fee_admin'] ?? 0,
            'cancel_booking_fee' => $data['cancel_booking_fee'] ?? 0,
            'isActive' => $data['isActive'],
        ]);

        // Hapus benefit lama
        MembershipPlanBenefit::where('odata_membership', $membership->odata)->delete();
        // Simpan benefit baru
        if (!empty($data['benefits']) && is_array($data['benefits'])) {
            foreach ($data['benefits'] as $benefit) {
                $benefits = MembershipBenefit::whereIn('odata', [$benefit['odata_benefit']])->first();
                MembershipPlanBenefit::create([
                    'odata' => (string) Str::uuid(),
                    'odata_membership' => $membership->odata,
                    'id_membership' => $membership->id,
                    'odata_benefit' => $benefit['odata_benefit'],
                    'id_benefit' => $benefits->id,
                    'value' => $benefit['value'],
                ]);
            }
        }
        activity()
            ->performedOn($membership)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated membership');
        return $membership;
    }

    public function delete($odata)
    {
        $membership = Membership::where('odata', $odata)->first();
        MembershipPlanBenefit::where('odata_membership', $membership->odata)->delete();
        Membership::where('odata', $odata)->delete();
        if (!$membership) {
            throw new HttpResponseException(response()->json(['error' => 'Membership not found'], 404));
        }
        $membership->delete();
        activity()
            ->performedOn($membership)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted membership');
        return true;
    }
}
