<?php

declare(strict_types=1);

namespace App\Dto\FilmCopy;

class ResultReservationDto
{
    public function __construct(
        public int   $reservationId,
        public int   $reservationNumber,
        public int   $userId,
        public string $date,
        public array $seats,
        public int $performanceId,
        public int $structureId,
    ) {

    }
}
