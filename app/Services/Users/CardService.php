<?php

namespace App\Services\Users;


use Carbon\Carbon;
use App\Repositories\Users\CardRepository;
use App\Services\Export\UserTicketSoftService;
use App\Dto\User\CardUserDto;
use App\Dto\User\CardResponseDto;
use App\Enums\Card\LoyaltyProgram;
class CardService
{
    
    public function __construct(
        protected CardRepository $cardRepository,
        protected UserTicketSoftService $userTicketSoftService
    )
    {
    }


    /**
     * @return CardResponseDto
     */
    public function getCards(): CardResponseDto
    {
        $user = \Auth::user();
        $cards = $this->cardRepository->getCardsByUserId($user->id);
        $cardResponse = new CardResponseDto();
        $cards->each(function($card) use ($user,$cardResponse){
            $res = $this->userTicketSoftService->getCardB($card->number);
            if (in_array(str_replace(" ", "_", $res->SellCountersResult->loyaltyprogramname), array_column(LoyaltyProgram::ALL_PROGRAM,"code"))) {
               $key = array_search($res->SellCountersResult->loyaltyprogramname, array_column(LoyaltyProgram::ALL_PROGRAM, 'code'));
               $code = array_column(LoyaltyProgram::ALL_PROGRAM, 'code')[$key];
               $nameProgram = array_column(LoyaltyProgram::ALL_PROGRAM, 'name')[$key];
                $cardResponse->$code[] = new CardUserDto(
                    $res->SellCountersResult->balance,
                    $card->number,
                    $user->name,
                    $card->issue_date,
                    $nameProgram,
                    ""
                );
            }
        });

        return  $cardResponse;
    }
}