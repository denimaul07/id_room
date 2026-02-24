<?php
namespace App\Services\Member;

use App\Models\User;


class MemberService
{
    public function list($search = null, $pagging = 10)
    {
        return User::with('userMemberships','userMemberships.membership','roles')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['users']);
            })->paginate($pagging);
    }

}
