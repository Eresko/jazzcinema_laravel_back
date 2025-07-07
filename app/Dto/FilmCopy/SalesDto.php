<?php

declare(strict_types=1);

namespace App\Dto\FilmCopy;

use App\Enums\Booking\RepaymentStatus;
use App\Enums\Booking\PaidStatus;
class SalesDto
{
    public function __construct(
        public int   $reservationId,
        public int   $reservationNumber,
        public int   $userId,
        public string $date,
        public array $seats,
        public int $performanceId,
        public int $structureId,
        public RepaymentStatus $repaymentStatus,
        public PaidStatus $paidStatus,
    ) {

    }
}
