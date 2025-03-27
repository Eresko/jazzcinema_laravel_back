<?php

namespace App\Repositories\Films;


use Carbon\Carbon;
use App\Models\Booking;
use App\Models\FilmCopy;
use Illuminate\Support\Collection;
use App\Dto\FilmCopy\ResultReservationDto;


class BookingRepository
{

    public function create(ResultReservationDto $dto): Booking
    {
        return Booking::create(
            [
                'user_id' => $dto->userId,
                'reservation_id' => $dto->reservationId,
                'reservation_number' => $dto->reservationNumber,
                'external_performance_id' => $dto->performanceId,
                'structure_element_id' => $dto->structureId,
                'date' => $dto->date,
                'seats' => $dto->seats,
            ],
        );
    }

    /**
     * @param int $userId
     * @param string $search
     * @return mixed
     */
    public function getReservationByUserId(int $userId,string | null $search):Collection {


        if (($search != null) && (strlen($search) > 1)) {
            return FilmCopy::query()
                ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
                ->Join('bookings', 'bookings.external_performance_id', '=', 'schedules.external_performance_id')
                ->where('film_copies.name','like','%'.$search.'%')
                ->where('user_id',$userId)
                ->select([
                    'film_copies.name AS name',
                    'bookings.structure_element_id AS structure_element_id',
                    'bookings.date AS date',
                    'bookings.id AS id',
                    'bookings.external_performance_id AS external_performance_id',
                    'bookings.reservation_number AS reservation_number',
                    'schedules.price AS price',
                    'schedules.start_time AS time',
                    'schedules.start_date AS show_date',
                    'bookings.seats AS seats',
                ])->orderBy('bookings.date','DESC')
                ->get();
        }

        return FilmCopy::query()
            ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
            ->Join('bookings', 'bookings.external_performance_id', '=', 'schedules.external_performance_id')
            ->where('user_id',$userId)
            ->select([
                'film_copies.name AS name',
                'bookings.structure_element_id AS structure_element_id',
                'bookings.date AS date',
                'bookings.id AS id',
                'bookings.external_performance_id AS external_performance_id',
                'bookings.reservation_number AS reservation_number',
                'schedules.price AS price',
                'schedules.start_time AS time',
                'schedules.start_date AS show_date',
                'bookings.seats AS seats',
            ])->orderBy('bookings.date','DESC')
            ->get();
        return FilmCopy::search($search, static function ($builder) use ($userId) {
            return $builder;
            return $builder->Join('schedules', 'schedules.external_performance_id', '=', 'schedules.external_performance_id');
        })->get();
    }

    /**
     * @param string|null $search
     * @param string $dateStart
     * @param string $dateEnd
     * @return Collection
     */
    public function getReservation(string | null $search,string $dateStart,string $dateEnd):Collection {


        if (($search != null) && (strlen($search) > 1)) {
            return FilmCopy::query()
                ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
                ->Join('bookings', 'bookings.external_performance_id', '=', 'schedules.external_performance_id')
                ->where('film_copies.name','like','%'.$search.'%')
                ->where('bookings.date','>=',$dateStart)
                ->where('bookings.date','<=',$dateEnd)
                ->select([
                    'film_copies.name AS name',
                    'bookings.structure_element_id AS structure_element_id',
                    'bookings.date AS date',
                    'bookings.id AS id',
                    'bookings.external_performance_id AS external_performance_id',
                    'bookings.reservation_number AS reservation_number',
                    'schedules.price AS price',
                    'schedules.start_time AS time',
                    'schedules.start_date AS show_date',
                    'bookings.seats AS seats',
                ])->orderBy('bookings.date','DESC')
                ->get();
        }

        return FilmCopy::query()
            ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
            ->Join('bookings', 'bookings.external_performance_id', '=', 'schedules.external_performance_id')
            ->where('bookings.date','>=',$dateStart)
            ->where('bookings.date','<=',$dateEnd)
            ->select([
                'film_copies.name AS name',
                'bookings.structure_element_id AS structure_element_id',
                'bookings.date AS date',
                'bookings.id AS id',
                'bookings.external_performance_id AS external_performance_id',
                'bookings.reservation_number AS reservation_number',
                'schedules.price AS price',
                'schedules.start_time AS time',
                'schedules.start_date AS show_date',
                'bookings.seats AS seats',
            ])->orderBy('bookings.date','DESC')
            ->get();
        return FilmCopy::search($search, static function ($builder) use ($userId) {
            return $builder;
            return $builder->Join('schedules', 'schedules.external_performance_id', '=', 'schedules.external_performance_id');
        })->get();
    }

    /**
     * @param int $id
     * @return FilmCopy
     */
    public function getReservationById(int $id):FilmCopy {
        return FilmCopy::query()
            ->Join('schedules', 'film_copies.external_film_copy_id', '=', 'schedules.external_film_copy_id')
            ->Join('bookings', 'bookings.external_performance_id', '=', 'schedules.external_performance_id')
            ->where('bookings.id',$id)
            ->select([
                'film_copies.name AS name',
                'bookings.structure_element_id AS structure_element_id',
                'bookings.date AS date',
                'bookings.id AS id',
                'bookings.external_performance_id AS external_performance_id',
                'bookings.reservation_number AS reservation_number',
                'schedules.price AS price',
                'schedules.start_time AS time',
                'schedules.start_date AS show_date',
                'bookings.seats AS seats',
            ])->orderBy('bookings.date','DESC')
            ->first();
    }
}