<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Services\Crypt\CryptService;

class UserRepository
{

    /**
     * @param string $login
     * @param string $password
     * @return User|null
     */
    public function getByLoginAndPassword(string $login,string $password):User | null {
        $users = User::query()->where('name',$login)->get();
        return $users->map( function($user) use ($password){
            $passwordEncrypt =  app(CryptService::class)->decode($user->password);

            if ($password == $passwordEncrypt) {
                return  $user;
            }
            return null;

        })->whereNotNull()->first();
    }


    /**
     * @param string $phone
     * @param string $password
     * @return User|null
     */
    public function getByPhoneAndPassword(string $phone,string $password):User | null {
        $users = User::query()->where('phone',$phone)->get();

        return $users->map( function($user) use ($password){
            file_put_contents(storage_path().'/A_P_1.log', print_r([$password,app(CryptService::class)->decode($user->password)], true ), FILE_APPEND | LOCK_EX); // вывод информации
            $passwordEncrypt =  app(CryptService::class)->decode($user->password);

            if ($password == $passwordEncrypt) {
                return  $user;
            }
            return null;

        })->whereNotNull()->first();
    }


    /**
     * @param string $phone
     * @return User
     */
    public function getUserByPhone(string $phone):User {
        return User::query()->where('phone',$phone)->first();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id) {
        return User::query()->where('id',$id)->first();
    }

    /**
     * @param string|null $search
     * @return Collection
     */
    public function getBySearch(string | null $search):Collection {
        if (strlen($search) > 1) {
           return User::query()
                ->where('name','like','%'.$search.'%')
                ->get();

        }
        else {
            return User::query()->get();
        }
    }


}