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
use App\Repositories\Films\ScheduleRepository;

class BookingServices
{

    public function __construct(
        protected BookingReservationService $bookingReservationService,
        protected CardRepository            $cardRepository,
        protected PaginatorService          $paginatorService,
        protected BookingRepository         $bookingRepository,
        protected ScheduleRepository        $scheduleRepository,
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


    public function getReservationByUser(User $user,int $page,string | null $search) {

        $bookings = $this->bookingRepository->getReservationByUserId($user->id,$search);
        $bookings = $this->paginatorService->toPagination($bookings, $page);
        $structureIds = $bookings->data->pluck('structure_element_id')->unique();
        $performance = $this->scheduleRepository->getByExternalPerformanceIds($bookings->data->pluck('external_performance_id'));
        $halls = $this->hallRepository->getByStructureElementIds($structureIds);
        $bookings->data = $bookings->data->map( function ($booking) use ($halls,$performance){
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
                $performanceKey = $performance->pluck('external_performance_id')->search($booking->external_performance_id);
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
                    true,
                    Carbon::parse($performance[$performanceKey]->start_date.' '.$performance[$performanceKey]->start_time)->format('d.m.Y H:i')
                );
        });


        return $bookings;
    }

    /**
     * @param int $page
     * @param string|null $search
     * @param string $dateStart
     * @param string $dateEnd
     */
    public function getReservation(int $page,string | null $search,string $dateStart,string $dateEnd) {
        $dateStart = Carbon::parse($dateStart);
        $dateEnd = Carbon::parse($dateEnd)->addDay();
        $bookings = $this->bookingRepository->getReservation($search,$dateStart,$dateEnd);
        $bookings = $this->paginatorService->toPagination($bookings, $page);
        $structureIds = $bookings->data->pluck('structure_element_id')->unique();
        $halls = $this->hallRepository->getByStructureElementIds($structureIds);
        $performance = $this->scheduleRepository->getByExternalPerformanceIds($bookings->data->pluck('external_performance_id'));
        $bookings->data = $bookings->data->map( function ($booking) use ($halls,$performance){
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
            $performanceKey = $performance->pluck('external_performance_id')->search($booking->external_performance_id);
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
                true,
                Carbon::parse($performance[$performanceKey]->start_date)->format('d.m.Y H:i')
            );
        });


        return $bookings;
    }


    /**
     * @param int $id
     * @return ReservationHistoryDto
     */
    public function getById(int $id):ReservationHistoryDto {
        $booking = $this->bookingRepository->getReservationById($id);
        $hall = $this->hallRepository->getByStructureElementIds(collect([$booking->structure_element_id]))->first();
        $performance = $this->scheduleRepository->getByExternalPerformanceIds(collect([$booking->external_performance_id]))->first();
        $structure = unserialize($hall->hall_structure);
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
            $hall->name,
            true,
            Carbon::parse($performance->start_date)->format('d.m.Y H:i')
        );
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

