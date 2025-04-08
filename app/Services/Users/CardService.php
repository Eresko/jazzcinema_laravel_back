<?php

namespace App\Services\Users;


use Carbon\Carbon;
use App\Repositories\Users\CardRepository;
use App\Services\Export\UserTicketSoftService;
use App\Dto\User\CardUserDto;
use App\Dto\User\CardResponseDto;
use App\Enums\Card\LoyaltyProgram;
use App\Repositories\Users\UserRepository;


class CardService
{
    
    public function __construct(
        protected CardRepository $cardRepository,
        protected UserTicketSoftService $userTicketSoftService,
        protected UserRepository $userRepository
    )
    {
    }


    /**
     * @return CardResponseDto
     */
    public function getCards(): CardResponseDto
    {
        $user = \Auth::user();
        return  $this->getCardsByUserIdAndName($user->id,$user->name);
    }

    public function getCardsByUserId(int $userId) {
        $user = $this->userRepository->getById($userId);
        $card = $this->getCardsByUserIdAndName($user->id,$user->name);
        $cards = [];
        foreach ($card as $item) {
            if (!empty($item[0]))
                $cards[] = $item[0];
        }
        return $cards;
    }

    public function getCardsByUserIdAndName(int $userId,string $name):CardResponseDto {
        $cards = $this->cardRepository->getCardsByUserId($userId);
        $cardResponse = new CardResponseDto();
        $cards->each(function($card) use ($name,$cardResponse){
            $res = $this->userTicketSoftService->getCardB($card->number);
            if (in_array(str_replace(" ", "_", $res->SellCountersResult->loyaltyprogramname), array_column(LoyaltyProgram::ALL_PROGRAM,"code"))) {
                $key = array_search($res->SellCountersResult->loyaltyprogramname, array_column(LoyaltyProgram::ALL_PROGRAM, 'code'));
                $code = array_column(LoyaltyProgram::ALL_PROGRAM, 'code')[$key];
                $nameProgram = array_column(LoyaltyProgram::ALL_PROGRAM, 'name')[$key];
                $cardResponse->$code[] = new CardUserDto(
                    $res->SellCountersResult->balance,
                    $card->number,
                    $name,
                    $card->issue_date,
                    $nameProgram,
                    ""
                );
            }
        });
        return $cardResponse;
    }
}