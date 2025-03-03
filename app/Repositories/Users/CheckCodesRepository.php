<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\CheckCodes;
use Illuminate\Support\Collection;

class CheckCodesRepository
{

    public function createOrUpdate(string $phone, string $code,int | null $userId = null)
    {

        return CheckCodes::updateOrCreate(
            [
            'phone' => $phone
            ],
            [
            'code' => $code,
            'user_id' => $userId
            ]
        );

    }


    /**
     * @param string $code
     * @return CheckCodes|null
     */
    public function getCode(string $code):CheckCodes | null{
        return CheckCodes::query()->where('code',$code)->where('updated_at','>',Carbon::now()->subMinutes(30))->first();
    }

}