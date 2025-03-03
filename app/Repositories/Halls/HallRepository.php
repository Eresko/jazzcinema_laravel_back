<?php

namespace App\Repositories\Halls;


use Carbon\Carbon;
use App\Models\Hall;
use Illuminate\Support\Collection;
class HallRepository
{

    /**
     * @return Collection
     */
    public function getAll():Collection
    {
        return Hall::query()->get();

    }

    /**
     * @param int $number
     * @return Hall
     */
    public function getByNumber(int $number):Hall {

        return Hall::query()->where('name',$number.' Зал')->first();
    }


    /**
     * @param int $structureElementId
     * @return Hall
     */
    public function getByStructureElementId(int $structureElementId):Hall {

        return Hall::query()->where('structure_id',$structureElementId)->first();
    }

    /**
     * @param Collection $structureElementIds
     * @return Collection
     */
    public function getByStructureElementIds(Collection $structureElementIds):Collection {

        return Hall::query()->whereIn('structure_id',$structureElementIds)->get();
    }
}