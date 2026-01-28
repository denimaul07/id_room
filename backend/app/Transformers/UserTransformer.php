<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;
class UserTransformer extends TransformerAbstract {
    public function transform(User $user){
        return [
            'id' => (int)$user->id,
            'email' => $user->email,
            'username' => $user->name,
            'kode' => $user->kode,

        ];
    }
}
