<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;


class UserRepository
{

    public function getByLoginAndPassword(string $login,string $password) {

        return User::query()->where('name',$login)->where('password',$password)->first();
    }


    /**
     * @param string $phone
     * @return User
     */
    public function getUserByPhone(string $phone):User {
        return User::query()->where('phone',$phone)->first();
    }
}