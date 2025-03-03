<?php

declare(strict_types=1);

namespace App\Dto\Schedule;

class ScheduleExportDto
{
    public function __construct(
        public int      $structureElementId,
        public string   $startTime,
        public int      $price,
        public string   $startDate,
        public int      $externalFilmCopyId,
        public int      $performanceId,
        public string   $hall
    ) {

    }
}
