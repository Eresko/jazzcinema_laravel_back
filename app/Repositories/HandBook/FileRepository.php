<?php

namespace App\Repositories\HandBook;


use Carbon\Carbon;
use App\Models\File;
use Illuminate\Support\Collection;
class FileRepository
{
    public function create($name,string $type,int $typeId):File
    {
        return File::query()->create([
            'name' => $name,
            'type' => $type,
            'type_id' => $typeId
        ]);

    }
    public function getById(int $id,string $type) {
        return File::query()->where('type_id',$id)->where('type',$type)->first();
    }


    public function delete(int $id,string $type) {
        return File::query()->where('type_id',$id)->where('type',$type)->first()->delete();
    }


}