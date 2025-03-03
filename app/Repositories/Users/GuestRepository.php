<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Dto\User\GuestExportDto;

class GuestRepository
{

    public function getByLoginAndPassword(string $login, string $password)
    {

        return User::query()->where('name', $login)->where('password', $password)->first();
    }

    public function create(GuestExportDto $dto)
    {

        $user =  User::updateOrCreate(
            [
                'phone' => $dto->phone,
                'role_id' => 2,
            ],
            [
                'name' => empty($dto->name) ? "пользователь " . $dto->phone : $dto->name,
                'email' => empty($dto->email) ? "email@".$dto->phone : $dto->email,
                'external_id' => $dto->externalId,
                'gender' => $dto->gender,
                'birthday' => Carbon::parse($dto->birthday)->format('Y-m-d'),
                'password' => "111111",
            ],
        );
        if (!empty($dto->cards)) {
            foreach ($dto->cards as $card) {
                app(CardRepository::class)->createOrUpdate($card,$user->id);
            }
        }
        return $user;
    }
}