<?php

declare(strict_types=1);

namespace App\Dto\Schedule;

use Carbon\Carbon;

class GetScheduleDto
{
    public function __construct(
        public int         $page,
        public string|null $search,
        public Carbon      $startTime,
        public Carbon      $endTime,
    ) {

    }
}
