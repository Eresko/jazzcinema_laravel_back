<?php

namespace App\Services\Paginator;

use Illuminate\Support\Collection;

class PaginatorService
{
    public function __construct()
    {
    }

    public function toPagination(Collection $data, int $currentPage = 1, int $perPage = 10): object
    {
        $obj = $this->createObject();
        $currentPage = $currentPage < 0 ? 1 : $currentPage;
        $remainder = $data->count() % $perPage ? 1 : 0;
        $obj->currentPage = $currentPage;
        $obj->perPage = $perPage;
        $obj->total = $data->count();
        $obj->lastPage = floor($data->count() / $perPage) + $remainder;
        $obj->data = $data->forPage($currentPage, $perPage);

        return  $obj;
    }

    protected function createObject(): object
    {
        return new class() {
            public int $currentPage;
            public int $perPage;
            public int $total;
            public int $lastPage;
            public Collection $data;
        };
    }
}