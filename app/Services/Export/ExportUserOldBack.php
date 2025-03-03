<?php

namespace App\Services\Export;


use Carbon\Carbon;
use App\Dto\User\GuestExportDto;
use App\Dto\User\CardUserTicketSoftDto;
use App\Repositories\Users\GuestRepository;

class ExportUserOldBack
{

    public function __construct(
        protected GuestRepository $guestRepository,
        protected CursService $cursService
    )
    {
    }


    public function run()
    {
        $res = $this->cursService->get(config('services.api_old_back') . 'users/list/');
        $dto = $this->toDto(json_decode($res));
        $this->update($dto);
    }


    protected function update(array $dto):void {
        foreach ($dto as $item) {
            $this->guestRepository->create($item);
        }
    }

    public function toDto($res) {
        $guests = [];
        foreach ($res->result as $key => $item) {
            if (((int)$item->LOGIN) == 0) {
                continue;
            }
            $cards = [];
            if (!empty($item->UF_CARD[0])) {
                $temps = $item->UF_CARD;
                foreach ($temps as $temp) {
                    $cards[] = $this->cardToDto((array)unserialize($temp));
                }
            }
            $guests[] = new GuestExportDto(
                $item->NAME,
                $item->EMAIL,
                $item->LOGIN,
                $item->UF_ID_USER,
                $item->PERSONAL_GENDER == 'F',
                $item->PERSONAL_BIRTHDAY,
                $cards
            );
        }

        return $guests;
    }

    protected function cardToDto(array $card):CardUserTicketSoftDto {
        return  new CardUserTicketSoftDto(
            empty($card['id']) ? $card['ID'] : $card['id'],
            empty($card['loyalty_program_id']) ? $card['LoyaltyProgramID'] : $card['loyalty_program_id'],
            empty($card['number']) ? $card['CardNumber'] : $card['number'],
            $card['secret_code'] ?? $card['CardSecret'] ?? "",
            empty($card['pin_code']) ? $card['PINCode'] : $card['pin_code'],
            $card['is_valid'] ?? $card['IsValid'],
            !isset($card['issue_date']) ? Carbon::parse($card['IssueDate'])->format('Y-m-d H:i:s') :Carbon::parse($card['issue_date'])->format('Y-m-d H:i:s'),
        );
    }
    public function importGuest(array $dto) {

    }
}
