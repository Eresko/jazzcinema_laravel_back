<?php

declare(strict_types=1);

namespace App\Dto\User;

class CardUserTicketSoftDto
{
    public function __construct(
        public string $id,
        public int $loyaltyProgramId,
        public string $number,
        public string $secretCode,
        public string $pinCode,
        public bool   $isValid,
        public string $issueDate,
    ) {

    }
}
