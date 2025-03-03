<?php

declare(strict_types=1);

namespace App\Dto\User;

class PrivilegeDto
{
    public function __construct(
        public int $specified_reservation_limit,
        public int $spent_armor_limit,
    ) {

    }
}
