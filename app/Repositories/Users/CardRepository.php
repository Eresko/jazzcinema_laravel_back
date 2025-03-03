<?php

namespace App\Repositories\Users;


use Carbon\Carbon;
use App\Models\Card;
use Illuminate\Support\Collection;


class CardRepository
{

    public function createOrUpdate($dto,int $userId) {

        return Card::updateOrCreate(
            [
                'user_id' => $userId,
                'external_id' => $dto->id,
            ],
            [
                'loyalty_program_id' => $dto->loyaltyProgramId,
                'number' => $dto->number,
                'secret_code' => $dto->secretCode,
                'pin_code' => $dto->pinCode,
                'is_valid' => $dto->isValid,
                'issue_date' => $dto->issueDate,
            ],
        );
    }

    /**
     * @param int $userId
     * @return Card
     */
    public function getByUserId(int $userId):Card {
        return Card::query()->where('user_id',$userId)->first();
    }


    /**
     * @param int $userId
     * @return Collection
     */
    public function getCardsByUserId(int $userId):Collection {
        return Card::query()->where('user_id',$userId)->get();
    }

}