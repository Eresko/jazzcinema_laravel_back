<?php

namespace App\Services\FilmCopy;

use Carbon\Carbon;
use App\Dto\FilmCopy\ReservationDto;
use Illuminate\Support\Collection;
use App\Repositories\Films\BookingRepository;
use App\Repositories\Films\SaleRepository;
use App\Repositories\Users\CardRepository;
use App\Services\Export\BookingReservationService;
use App\Dto\FilmCopy\ResultReservationDto;
use App\Services\Paginator\PaginatorService;
use App\Repositories\Halls\HallRepository;
use App\Dto\User\ReservationHistoryDto;
use App\Models\User;
use App\Repositories\Films\ScheduleRepository;
use App\Enums\Booking\RepaymentStatus;
use App\Enums\Booking\PaidStatus;
use App\Dto\FilmCopy\SalesDto;
use Illuminate\Support\Facades\Http;
class SaleServices
{
    public function __construct(
        protected BookingReservationService $bookingReservationService,
        protected CardRepository            $cardRepository,
        protected PaginatorService          $paginatorService,
        protected BookingRepository         $bookingRepository,
        protected ScheduleRepository        $scheduleRepository,
        protected SaleRepository            $saleRepository,
        protected HallRepository            $hallRepository,
    )
    {
    }



    /**
     * @param int $page
     * @param string|null $search
     * @param string $dateStart
     * @param string $dateEnd
     */
    public function getSale(int $page, string | null $search, string $dateStart, string $dateEnd)
    {
        $dateStart = Carbon::parse($dateStart);
        $dateEnd = Carbon::parse($dateEnd)->addDay();
        $sales = $this->saleRepository->getSale($search, $dateStart, $dateEnd);
        $sales = $this->paginatorService->toPagination($sales, $page);
        $structureIds = $sales->data->pluck('structure_element_id')->unique();
        $halls = $this->hallRepository->getByStructureElementIds($structureIds);
        $performance = $this->scheduleRepository->getByExternalPerformanceIds($sales->data->pluck('external_performance_id'));
        $sales->data = $sales->data->map(function ($sale) use ($halls, $performance) {
            $searchHall = $halls->pluck('structure_id');
            $keyHall = $searchHall->search((string)$sale->structure_element_id);
            $structure = unserialize($halls[$keyHall]->hall_structure);

            $seatNumber = [];
            foreach (json_decode($sale->seats) as $itemSeat) {
                $key = array_search($itemSeat, array_column($structure, 'name'));
                $seatNumber[] = [
                    'label' => $structure[$key]['label'],
                    'row' => $structure[$key]['row'],
                ];
            }
            $performanceKey = $performance->pluck('external_performance_id')->search($sale->external_performance_id);
            return new ReservationHistoryDto(
                $sale->structure_element_id,
                Carbon::parse($sale->date)->format('d.m.Y H:i'),
                $sale->id,
                $sale->external_performance_id,
                $sale->name,
                $sale->reservation_number,
                $sale->price,
                json_decode($sale->seats),
                $seatNumber,
                Carbon::parse($sale->time)->format('H:i'),
                $halls[$keyHall]->name,
                true,
                Carbon::parse($performance[$performanceKey]->start_date)->format('d.m.Y H:i'),
                $sale->payment_status,
                $sale->repayment_status,
            );
        });


        return $sales;
    }







}
