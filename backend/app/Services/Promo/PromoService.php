<?php

namespace App\Services\Promo;

use App\Models\Promo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PromoService
{
    public function list($search = null, $pagging = null)
    {
        return Promo::when($search, function($q) use ($search) {
                $q->where('odata', 'like', "%$search%")
                    ->orWhere('banner', 'like', "%$search%")
                    ->orWhere('isActive', $search);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function create(array $data)
    {
        if (isset($data['banner'])) {
            $imagePath = $data['banner']->store('promo', 'public');
        }

        $promo = Promo::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => 'd73e5120-5b9c-44c1-9154-b718794e8fc3',
            'banner' => $imagePath,
            'isActive' => $data['isActive'] ?? 0,
        ]);

        
        activity()
            ->performedOn($promo)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created promo');

        return $promo;
    }

    public function update($id, array $data)
    {
        $promo = Promo::findOrFail($id);
        if (isset($data['banner'])) {
            $imagePath = $data['banner']->store('promo', 'public');
            $data['banner'] = $imagePath;
        }   
        $promo->update($data);

        activity()
            ->performedOn($promo)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated promo');

        return $promo;
    }

    public function delete($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        activity()
            ->performedOn($promo)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $promo->toArray()])
            ->event('delete')
            ->log('deleted promo');

        return true;
    }

    public function show($id)
    {
        return Promo::findOrFail($id);
    }
}
