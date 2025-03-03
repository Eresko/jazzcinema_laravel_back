<?php

declare(strict_types=1);

namespace App\Dto\Schedule;

class ScheduleDto
{
    public function __construct(
        public int    $performanceId,
        public int    $StructureElementID,
        public int    $price,
        public string $time,
        public string $dateTime,
        public string $zal,
        public $timestamp,
        public string $name = "",
    ) {

    }
}
