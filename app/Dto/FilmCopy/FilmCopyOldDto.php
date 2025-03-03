<?php

declare(strict_types=1);

namespace App\Dto\FilmCopy;

class FilmCopyOldDto
{
    public function __construct(
        public int    $id,
        public string $name_film,
        public array  $actors,
        public string $posters,
        public string $start_date,
        public string $end_date,
        public array  $genre,
        public int    $time_film,
        public int    $filmCopy,
        public array  $director,
        public string $year_of_issue,
        public array $country,
        public string $description,
        public bool   $memorandum_show,
        public $schedule,
        public $P
    ) {

    }
}
