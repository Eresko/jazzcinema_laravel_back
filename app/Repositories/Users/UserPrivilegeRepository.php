<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\UserPrivilege;
use Illuminate\Support\Collection;


class UserPrivilegeRepository
{

    public function createOrUpdate(int $userId) {

        return UserPrivilege::updateOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'specified_sales_limit' => 5,
                'specified_reservation_limit' => 5,
                'sales_allowed' => false,
            ],
        );
    }

    /**
     * @param int $userId
     * @return UserPrivilege|null
     */
    public function getByUserId(int $userId):UserPrivilege | null {
        return UserPrivilege::query()->where('user_id',$userId)->first();
    }




}