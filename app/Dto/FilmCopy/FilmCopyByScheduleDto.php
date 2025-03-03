<?php

declare(strict_types=1);

namespace App\Dto\FilmCopy;

class FilmCopyByScheduleDto
{
    public function __construct(
        public string $name_film,
        public int    $filmCopyId,
        public int    $id,
        public string $genre,
        public $schedule_time
    ) {

    }
}
