<?php

declare(strict_types=1);

namespace App\Dto\User;

class ReservationHistoryDto
{
    public function __construct(
        public int $StructureElementID,
        public string $date_time,
        public string $id,
        public int $performanceId,
        public string $name_film,
        public int $number_reservation,
        public int $price,
        public array $seats,
        public array $seats_seans,
        public string $time,
        public $zal,
        public bool $show_date,
        public string | null $dateStart = null,
    ) {

    }
}
