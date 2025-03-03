<?php

namespace App\Repositories\HandBook;

use Carbon\Carbon;
use App\Models\Banner;
use Illuminate\Support\Collection;
class BannerRepository
{


    /**
     * @return Collection
     */
    public function getAll():Collection
    {
        return Banner::query()->get();

    }
    /**
     * @param string $name
     * @param string $start
     * @param string $end
     * @return Banner
     */
    public function createBanner(string $name,string $start,string $end ):Banner {
        return Banner::create([
            'name' => $name,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function getById(int $id):Banner
    {
        return Banner::query()->where('id',$id)->first();

    }


    public function updateById(int $id,string $name,string $start,string $end):bool {
        $banner = Banner::query()->where('id',$id)->first();
        return $banner->update([
           'name' => $name,
           'start' => $start,
           'end' => $end,
        ]);

    }

}