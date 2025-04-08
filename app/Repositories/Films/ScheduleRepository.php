<?php

namespace App\Repositories\Films;


use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Support\Collection;
use App\Dto\Schedule\ScheduleExportDto;


class ScheduleRepository
{

    public function create(ScheduleExportDto $dto):Schedule {
        return Schedule::updateOrCreate(
            [
                'external_performance_id'   => $dto->performanceId,
            ],
            [
                'structure_element_id'     => $dto->structureElementId,
                'start_time' => $dto->startTime,
                'price'    => $dto->price,
                'start_date'   => $dto->startDate,
                'external_film_copy_id'       => $dto->externalFilmCopyId,
                'hall'   => $dto->hall,
            ],
        );
    }

    /**
     * @return Collection
     */
    public function list():Collection {
       return  Schedule::query()->get();
    }

    /**
     * @param int $id
     * @return Schedule
     */
    public function getById(int $id):Schedule {
        return Schedule::query()->where('id',$id)->first();
    }

    /**
     * @param string $date
     * @return Collection
     */
    public function getByDate(string $date):Collection {
        return Schedule::query()->where('start_date',$date)->get();
    }

    /**
     * @return Collection
     */
    public function getCurrent():Collection {
        return  Schedule::query()->where('start_date','>=',Carbon::now()->format('Y-m-d'))->orderBy("start_date")->orderBy("start_time")->get();
    }

    /**
     * @param int $filmCopyId
     * @return Collection
     */
    public function getCurrentByFilmCopyId(int $filmCopyId):Collection {
        return  Schedule::query()->where('start_date','>=',Carbon::now()->format('Y-m-d'))->where('external_film_copy_id',$filmCopyId)->orderBy("start_date")->orderBy("start_time")->get();
    }

    /**
     * @param int $externalPerformanceId
     * @return Schedule|null
     */
    public function getByExternalId(int $externalPerformanceId):Schedule | null {
        return Schedule::query()->where('external_performance_id',$externalPerformanceId)->first();
    }


    /**
     * @param int $externalPerformanceId
     * @return int|null
     */
    public function getStructureElementIdByExternalId(int $externalPerformanceId):int | null {
        return Schedule::query()->where('external_performance_id',$externalPerformanceId)->first()->structure_element_id;
    }

    /**
     * @param Collection $ids
     * @return Collection
     */
    public function getByExternalPerformanceIds(Collection $getByExternalPerformanceIds):Collection {
        return Schedule::query()->whereIn('external_performance_id',$getByExternalPerformanceIds)->get()->unique('id');
    }

}