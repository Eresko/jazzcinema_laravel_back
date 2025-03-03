<?php

namespace App\Services\FilmCopy;


use Carbon\Carbon;

use App\Dto\FilmCopy\ReservationDto;
use Illuminate\Support\Collection;
use App\Repositories\Films\BookingRepository;
use App\Repositories\Users\CardRepository;
use App\Services\Export\BookingReservationService;
use App\Dto\FilmCopy\ResultReservationDto;
use App\Services\Paginator\PaginatorService;
use App\Repositories\Halls\HallRepository;
use App\Dto\User\ReservationHistoryDto;
use App\Models\User;

class BookingServices
{

    public function __construct(
        protected BookingReservationService $bookingReservationService,
        protected CardRepository            $cardRepository,
        protected PaginatorService          $paginatorService,
        protected BookingRepository         $bookingRepository,
        protected HallRepository            $hallRepository,
    )
    {
    }


    /**
     * @param ReservationDto $dto
     * @return bool|ResultReservationDto
     */
    public function reservationNotSelect(ReservationDto $dto):bool | ResultReservationDto{
        $user = \Auth::user();
        $card = $this->cardRepository->getByUserId($user->id);
        $dto->userId = $user->id;
        $dto->externalId = $user->external_id;
        $dto->number = $card->number;
        $dto->name = $user->name;
        return $this->reservation($dto);
    }


    public function getReservationByUser(User $user,int $page,string $search) {

        $bookings = $this->bookingRepository->getReservation($user->id,$search);
        $bookings = $this->paginatorService->toPagination($bookings, $page);
        $structureIds = $bookings->data->pluck('structure_element_id')->unique();
        $halls = $this->hallRepository->getByStructureElementIds($structureIds);
        $bookings->data = $bookings->data->map( function ($booking) use ($halls){
                    $searchHall = $halls->pluck('structure_id');
                    $keyHall = $searchHall->search((string)$booking->structure_element_id);
                    $structure = unserialize($halls[$keyHall]->hall_structure);

                    $seatNumber = [];
                    foreach (json_decode($booking->seats) as $itemSeat) {
                        $key = array_search($itemSeat,array_column($structure,'name'));
                        $seatNumber[] = [
                          'label' => $structure[$key]['label'],
                          'row' => $structure[$key]['row'],
                        ];
                    }

                return new ReservationHistoryDto(
                    $booking->structure_element_id,
                    Carbon::parse($booking->date)->format('d.m.Y H:i'),
                    $booking->id,
                    $booking->external_performance_id,
                    $booking->name,
                    $booking->reservation_number,
                    $booking->price,
                    json_decode($booking->seats),
                    $seatNumber,
                    Carbon::parse($booking->time)->format('H:i'),
                    $halls[$keyHall]->name,
                    true
                );
        });


        return $bookings;
    }

    /**
     * @param ReservationDto $dto
     * @return bool|ResultReservationDto
     */
    protected function reservation(ReservationDto $dto):bool | ResultReservationDto{
        
        $timeReservation = \date('Y-m-d H:i:00');
        $query = [
            'cinemaId'=> 1, //id
            'cardNumber'=> $dto->number, // номер карты
            'seanceId' => $dto->idPerformance, //id сеанса
            'containerId' => $dto->zalId, //id зала
            'seats' =>  implode(',',$dto->selectIds), // список Id
            'customerId'=> $dto->externalId ,//id клюента,
            'client' => $dto->name.' бронь '.$timeReservation,
            'internalId' => $this->generateSession()
        ];
        $result = $this->bookingReservationService->reserveSeats2($query);
        if (empty($result->ReserveSeats2Result->reservationnumber)) {
            return false;
        }
        $reservationDto = $this->toDtoReservation($result, $dto);
        $this->bookingRepository->create($reservationDto);
        return $reservationDto;

    }

    /**
     * @param $result
     * @param ReservationDto $dto
     * @return ResultReservationDto
     */
    protected function toDtoReservation($result,ReservationDto $dto):ResultReservationDto {
        return new ResultReservationDto(
            $result->ReserveSeats2Result->reservationid,
            $result->ReserveSeats2Result->reservationnumber,
            $dto->userId,
            Carbon::now()->format('Y-m-d H:i:s'),
            $dto->selectIds,
            $dto->idPerformance,
            $dto->zalId
        );
    }

    /**
     * @return int
     */
    protected function generateSession():int {
        return rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }
}

