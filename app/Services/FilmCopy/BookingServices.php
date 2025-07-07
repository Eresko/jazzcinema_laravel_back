<?php

namespace App\Services\FilmCopy;

use App\Models\FilmCopy;
use Carbon\Carbon;
use App\Dto\FilmCopy\ReservationDto;
use Illuminate\Support\Collection;
use App\Repositories\Films\BookingRepository;
use App\Repositories\Users\CardRepository;
use App\Services\Export\BookingReservationService;
use App\Dto\FilmCopy\ResultReservationDto;
use App\Repositories\Films\SaleRepository;
use App\Services\Paginator\PaginatorService;
use App\Repositories\Halls\HallRepository;
use App\Dto\User\ReservationHistoryDto;
use App\Models\User;
use App\Repositories\Films\ScheduleRepository;
use App\Enums\Booking\RepaymentStatus;
use App\Enums\Booking\PaidStatus;
use App\Dto\FilmCopy\SalesDto;
use Illuminate\Support\Facades\Http;
class BookingServices
{
    public function __construct(
        protected BookingReservationService $bookingReservationService,
        protected CardRepository            $cardRepository,
        protected PaginatorService          $paginatorService,
        protected BookingRepository         $bookingRepository,
        protected ScheduleRepository        $scheduleRepository,
        protected SaleRepository            $saleRepository,
        protected HallRepository            $hallRepository,
    ) {
    }


    /**
     * @param ReservationDto $dto
     * @return bool|ResultReservationDto
     */
    public function reservationNotSelect(ReservationDto $dto): bool | ResultReservationDto
    {
        $user = \Auth::user();
        $card = $this->cardRepository->getByUserId($user->id);
        $dto->userId = $user->id;
        $dto->externalId = $user->external_id;
        $dto->number = $card->number;
        $dto->name = $user->name;
        return $this->reservation($dto);
    }

    public function paidNotReservation(ReservationDto $dto)
    {
        $res = $this->reservationNotSelect($dto);
        return $this->bookingReservationService->confirmReservation($res->reservationId);
    }



    public function getReservationByUser(User $user, int $page, string | null $search)
    {

        $bookings = $this->bookingRepository->getReservationByUserId($user->id, $search);
        $bookings = $this->paginatorService->toPagination($bookings, $page);
       // file_put_contents(storage_path().'/A_BOOK.log', print_r($bookings->data->toArray(), true ), FILE_APPEND | LOCK_EX); // вывод информации
        $structureIds = $bookings->data->pluck('structure_element_id')->unique();
        $performance = $this->scheduleRepository->getByExternalPerformanceIds($bookings->data->pluck('external_performance_id'));
        $halls = $this->hallRepository->getByStructureElementIds($structureIds);
        $bookings->data = $bookings->data->map(function ($booking) use ($halls, $performance) {
            $searchHall = $halls->pluck('structure_id');
            $keyHall = $searchHall->search((string)$booking->structure_element_id);
            $structure = unserialize($halls[$keyHall]->hall_structure);

            $seatNumber = [];
            foreach (json_decode($booking->seats) as $itemSeat) {
                $key = array_search($itemSeat, array_column($structure, 'name'));
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
                Carbon::parse($performance[$performanceKey]->start_date.' '.$performance[$performanceKey]->start_time)->format('d.m.Y H:i'),
                $booking->payment_status,
                $booking->repayment_status,
                empty($booking->qr) ? null : config('services.app_url').'/img/qr/'.$booking->qr,
            );
        });

        $bookings->data = collect(array_values($bookings->data->toArray()));
        return $bookings;
    }

    /**
     * @param int $page
     * @param string|null $search
     * @param string $dateStart
     * @param string $dateEnd
     */
    public function getReservation(int $page, string | null $search, string $dateStart, string $dateEnd)
    {
        $dateStart = Carbon::parse($dateStart);
        $dateEnd = Carbon::parse($dateEnd)->addDay();
        $bookings = $this->bookingRepository->getReservation($search, $dateStart, $dateEnd);
        $bookings = $this->paginatorService->toPagination($bookings, $page);
        $structureIds = $bookings->data->pluck('structure_element_id')->unique();
        $halls = $this->hallRepository->getByStructureElementIds($structureIds);
        $performance = $this->scheduleRepository->getByExternalPerformanceIds($bookings->data->pluck('external_performance_id'));
        $bookings->data = $bookings->data->map(function ($booking) use ($halls, $performance) {
            $searchHall = $halls->pluck('structure_id');
            $keyHall = $searchHall->search((string)$booking->structure_element_id);
            $structure = unserialize($halls[$keyHall]->hall_structure);

            $seatNumber = [];
            foreach (json_decode($booking->seats) as $itemSeat) {
                $key = array_search($itemSeat, array_column($structure, 'name'));
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
    public function getById(int $id): ReservationHistoryDto
    {
        $booking = $this->bookingRepository->getReservationById($id);
        $hall = $this->hallRepository->getByStructureElementIds(collect([$booking->structure_element_id]))->first();
        $performance = $this->scheduleRepository->getByExternalPerformanceIds(collect([$booking->external_performance_id]))->first();
        $structure = unserialize($hall->hall_structure);
        $seatNumber = [];
        foreach (json_decode($booking->seats) as $itemSeat) {
            $key = array_search($itemSeat, array_column($structure, 'name'));
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
     * @return bool|array
     */

    public function paymentNotSelect(ReservationDto $dto):bool | array
    {
        $user = \Auth::user();
        $card = $this->cardRepository->getByUserId($user->id);
        $dto->userId = $user->id;
        $dto->externalId = $user->external_id;
        $dto->number = $card->number;
        $dto->name = $user->name;
        /**
         * Делаем обычное бронирование
         */
        $reservationDto = $this->reservation($dto);
        if (!$reservationDto) {
            return false;
        }
        /**
         * Делаем платное бронирование
         */
        $performance = $this->scheduleRepository->getByExternalId($reservationDto->performanceId);
        $reservationPaymentDto = $this->bookingReservationService->reservationPayed($reservationDto->reservationId,$performance->price * count($reservationDto->seats));


        /**
         * Здесь должны сделать ссыль на оплату
         */
        


        return [$reservationDto,$reservationPaymentDto,$this->completingSale($reservationDto->reservationId)];
    }

    public function checkTicket($reservationId):bool | FilmCopy {

        $booking = $this->bookingRepository->getReservationByReservationId($reservationId);
        if (empty($booking)) {
            $booking = $this->saleRepository->getReservationByReservationId($reservationId);
        }
        if (empty($booking)) {
            return false;
        }

            //$sales = $this->saleRepository->getReservationByReservationId($reservationId);
            $hall = $this->hallRepository->getByStructureElementIds(collect([$booking->structure_element_id]))->first();
            $structure = unserialize($hall->hall_structure);
            $seatNumber = [];
            foreach (json_decode($booking->seats) as $itemSeat) {
                $key = array_search($itemSeat, array_column($structure, 'name'));
                $seatNumber[] = [
                    'label' => $structure[$key]['label'],
                    'row' => $structure[$key]['row'],
                ];
            }
            $booking->seatNumber = $seatNumber;
            return $booking;

    }
    public function completingSale(int $reservationId) {
        $booking = $this->bookingRepository->getReservationByReservationId($reservationId);
        $seats = json_decode($booking->seats);
        $bookingReservation = $this->bookingReservationService->sellReservation($reservationId, count($seats) * $booking->price);
        if ($bookingReservation->SellReservationResult->reservationid) {
            $dto = $this->toCreateSalesDto($booking, $bookingReservation->SellReservationResult);
            $name = $booking->user_id.'-'.rand(1,999).'check-'.rand(1,999);
            $query = [
                'code' => config('services.url_qr').'/'.$reservationId  ,
                'name' => $name
            ];
            $resultSvg = Http::post(config('services.url_generate_qr'), $query);
            $file = $resultSvg->getBody()->getContents();
            $path =  storage_path() .'/app/public/img/qr/'.$name.'.svg';
            file_put_contents($path,$file);
            $this->saleRepository->create($dto,$name.'.svg');
            $this->bookingRepository->deleteById($booking->id);
        }


        return [$booking,$bookingReservation];
    }
    

    public function repay($reservationId) {
        $sales = $this->saleRepository->getSaleByReservationId($reservationId);
        $sales->update(['repayment_status'=> 'ACCEPT']);
    }
    protected function toCreateSalesDto($booking,$reservation) {
        return new SalesDto(
            $reservation->reservationid,
            $reservation->reservationnumber,
            $booking->user_id,
            Carbon::now()->format('Y-m-d H:i:s'),
            json_decode($booking->seats),
            $booking->external_performance_id,
            $booking->structure_element_id,
            RepaymentStatus::PENDING(),
            PaidStatus::PAID()
        );
    }
    
    /**
     * @param ReservationDto $dto
     * @return bool|ResultReservationDto
     */
    protected function reservation(ReservationDto $dto): bool | ResultReservationDto
    {

        $timeReservation = \date('Y-m-d H:i:00');
        $query = [
            'cinemaId' => 1, //id
            'cardNumber' => $dto->number, // номер карты
            'seanceId' => $dto->idPerformance, //id сеанса
            'containerId' => $dto->zalId, //id зала
            'seats' =>  implode(',', $dto->selectIds), // список Id
            'customerId' => $dto->externalId ,//id клюента,
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
    protected function toDtoReservation($result, ReservationDto $dto): ResultReservationDto
    {
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
    protected function generateSession(): int
    {
        return rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
    }
}
