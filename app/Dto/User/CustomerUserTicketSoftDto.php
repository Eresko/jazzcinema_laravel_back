<?php

declare(strict_types=1);

namespace App\Dto\User;

class CustomerUserTicketSoftDto
{
    public function __construct(
        public string $id,
        public int $rootCustomerId,
        public int $addressId,
        public string $firstName,
        public string $middleName,
        public string   $lastName,
        public string $gender,
        public string $birthDate,
    ) {

    }
}
