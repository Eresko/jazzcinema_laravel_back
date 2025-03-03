<?php

declare(strict_types=1);

namespace App\Dto\User;

class CardUserDto
{
    public function __construct(
        public int $balance,
        public string $number,
        public string $owner,
        public string $issue_date,
        public string $loyalty_programs,
        public string $picture,
    ) {

    }
}
