<?php

namespace App\Services\Halls;


use Carbon\Carbon;

use App\Repositories\Halls\HallRepository;
use Illuminate\Support\Collection;
use App\Repositories\Films\ScheduleRepository;
use App\Models\Hall;
use App\Dto\Hall\HallStructureDto;
class HallServices
{

    public function __construct(
        protected HallRepository $hallRepository,
        protected ScheduleRepository $scheduleRepository

    )
    {
    }


    /**
     * @return Collection
     */

    public function getScheme():Collection {

        $halls = $this->hallRepository->getAll();

        return $halls->map( function ($hall) {
           return [
               "id_tiket" => $hall->ticket_soft_id,
                "id" => $hall->name,
                "scheme" => unserialize($hall->hall_structure)
           ];
        });
    }


    /**
     * @param int $number
     * @return Collection
     */
    public function get(int $number):Collection {
        return $this->hallRepository->getByNumber($number);
    }


    /**
     * @param int $performanceId
     * @return HallStructureDto
     */
    public function getByPerformance(int $performanceId):HallStructureDto {
        $structureElementId = $this->scheduleRepository->getStructureElementIdByExternalId($performanceId);
        $hall = $this->hallRepository->getByStructureElementId($structureElementId);
        return new HallStructureDto(
            $hall->name,
            $hall->structure_id,
            unserialize($hall->hall_structure)
        );
    }


    /**
     * @return Collection
     */
    public function list():Collection {
        $halls = $this->hallRepository->getAll();
        return $halls->map( function ($hall) {
            return [
                "id" => $hall->id,
                "name" => $hall->name,
                "is_display_schedule" => (bool)$hall->is_display_schedule,
            ];
        });
    }

    /**
     * @param int $id
     * @param bool $isDisplaySchedule
     * @return bool
     */
    public function update(int $id, bool $isDisplaySchedule): bool
    {
        $halls = $this->hallRepository->getById($id);
        return $halls->update(['is_display_schedule' => $isDisplaySchedule]);
    }
}