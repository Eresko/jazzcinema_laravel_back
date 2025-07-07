<?php

namespace App\Repositories\Films;


use Carbon\Carbon;
use App\Models\Sale;
use App\Models\FilmCopy;
use Illuminate\Support\Collection;
use App\Dto\FilmCopy\SalesDto;


class SaleRepository
{

    public function create(SalesDto $dto,string $qr =""): Sale
    {
        return Sale::create(
            [
                'user_id' => $dto->userId,
                'reservation_id' => $dto->reservationId,
                'reservation_number' => $dto->reservationNumber,
                'external_performance_id' => $dto->performanceId,
                'structure_element_id' => $dto->structureId,
                'date' => $dto->date,
                'seats' => $dto->seats,
                'repayment_status' => $dto->repaymentStatus,
                'payment_status' => $dto->paidStatus,
                'qr' => $qr,
            ],
        );
    }

    public function getSaleByReservationId($reservationId) {
        return Sale::query()->where("reservation_id",$reservationId)->first();
    }

    public function getReservationByReservationId(int $reservationId) {
        return FilmCopy::query()
            ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
            ->join('sales', function($join) use($reservationId){
                $join->on('sales.external_performance_id', '=', 'schedules.external_performance_id');
                $join->where('sales.reservation_id',$reservationId);
            })
            ->Join('halls', 'halls.structure_id', '=', 'sales.structure_element_id')
            ->select([
                'film_copies.name AS name',
                'sales.structure_element_id AS structure_element_id',
                'sales.date AS date',
                'sales.id AS sales_id',
                'sales.id AS id',
                'sales.external_performance_id AS external_performance_id',
                'sales.reservation_number AS reservation_number',
                'schedules.price AS price',
                'schedules.start_time AS time',
                'schedules.start_date AS show_date',
                'sales.seats AS seats',
                'sales.qr AS qr',
                'sales.payment_status AS payment_status',
                'sales.repayment_status AS repayment_status',
                'halls.name AS name_hall',
            ])
            ->first();
    }

    public function getSale(string | null $search,string $dateStart,string $dateEnd):Collection
    {


        if (($search != null) && (strlen($search) > 1)) {
            return FilmCopy::query()
                ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
                ->Join('sales', 'sales.external_performance_id', '=', 'schedules.external_performance_id')
                ->where('film_copies.name', 'like', '%' . $search . '%')
                ->where('sales.date', '>=', $dateStart)
                ->where('sales.date', '<=', $dateEnd)
                ->select([
                    'film_copies.name AS name',
                    'sales.structure_element_id AS structure_element_id',
                    'sales.date AS date',
                    'sales.id AS id',
                    'sales.external_performance_id AS external_performance_id',
                    'sales.reservation_number AS reservation_number',
                    'schedules.price AS price',
                    'schedules.start_time AS time',
                    'schedules.start_date AS show_date',
                    'sales.seats AS seats',
                    'sales.seats AS seats',
                ])->orderBy('sales.date', 'DESC')
                ->get();
        }

        return FilmCopy::query()
            ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
            ->Join('sales', 'sales.external_performance_id', '=', 'schedules.external_performance_id')
            ->where('sales.date', '>=', $dateStart)
            ->where('sales.date', '<=', $dateEnd)
            ->select([
                'film_copies.name AS name',
                'sales.structure_element_id AS structure_element_id',
                'sales.date AS date',
                'sales.id AS id',
                'sales.external_performance_id AS external_performance_id',
                'sales.reservation_number AS reservation_number',
                'schedules.price AS price',
                'schedules.start_time AS time',
                'schedules.start_date AS show_date',
                'sales.seats AS seats',
                'sales.repayment_status AS repayment_status',
                'sales.payment_status AS payment_status',
            ])->orderBy('sales.date', 'DESC')
            ->get();
    }

}