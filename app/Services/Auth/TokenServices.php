<?php

namespace App\Services\Auth;


use Carbon\Carbon;
use App\Models\User;
use Firebase\JWT\JWT;

class TokenServices
{
    public function create(string $role,User $user):string
    {

        $tokenPayload = [
            'id' => $user->id,
            'role' => $role,
            'generation_date' => \Carbon\Carbon::now()
        ];
        return auth()->setTTL(720000000)->claims($tokenPayload)->login($user);

    }
}
