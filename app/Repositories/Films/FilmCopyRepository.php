<?php

namespace App\Repositories\Films;


use Carbon\Carbon;
use App\Models\FilmCopy;
use Illuminate\Support\Collection;
use App\Dto\FilmCopy\FilmCopyDto;
class FilmCopyRepository
{
    public function get(string | null $search):Collection
    {
        if ($search) {
            return FilmCopy::query()->where('name','like','%'.$search.'%')->orderBy('start_date','DESC')->get();
        }
        return FilmCopy::query()->orderBy('start_date','DESC')->get();

    }

    public function create(array $item) {

        FilmCopy::create([
            'name' => $item['name'],
            'duration' => $item['duration'],
            'url' => $item['url'],
            'start_date' => Carbon::parse($item['releasedate'])->format('Y-m-d'),
            'end_date' => Carbon::parse($item['disabled'])->format('Y-m-d'),
            'film_stills' => "",
            'ticket_soft_id' => $item['id'],
            'disabled' => Carbon::parse($item['disabled'])->format('Y-m-d'),
            'age' => $item['age']['display'],
            'full_age' => json_encode($item['age']),
            'external_film_copy_id'  => $item['filmcopyId'],

        ]);
    }


    public function getById(int $id):FilmCopy {
        return FilmCopy::query()->where('id',$id)->first();
    }


    /**
     * @param int $id
     * @param FilmCopyDto $dto
     * @return bool
     */
    public function update(int $id,FilmCopyDto $dto):bool {
        $filmCopy = FilmCopy::query()->where('id',$id)->first();
        if (empty($filmCopy)) {
            return false;
        }
        return $filmCopy->update($dto->toArray());
    }

    /**
     * @param Collection $ids
     * @return Collection
     */
    public function getByFilmCopyExternalIds(Collection $ids):Collection {
        return FilmCopy::query()->whereIn('external_film_copy_id',$ids)->get();
    }


    /**
     * @param int $id
     * @return FilmCopy
     */
    public function getByFilmCopyExternalId(int $id):FilmCopy {
        return FilmCopy::query()->where('external_film_copy_id',$id)->first();
    }
}