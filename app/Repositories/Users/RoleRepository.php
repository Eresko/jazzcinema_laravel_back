<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Collection;


class RoleRepository
{

    public function getById(int $id) {

        return Role::query()->where('id',$id)->first();
    }
}