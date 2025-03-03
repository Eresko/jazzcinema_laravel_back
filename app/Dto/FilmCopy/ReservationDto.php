<?php

declare(strict_types=1);

namespace App\Dto\FilmCopy;

class ReservationDto
{
    public int $userId = 0;
    public int $externalId = 0;
    public string $name = "";
    public string | null $number = null;

    public function __construct(
        public int   $idPerformance,
        public int   $zalId,
        public array $selectIds
    ) {

    }
}
