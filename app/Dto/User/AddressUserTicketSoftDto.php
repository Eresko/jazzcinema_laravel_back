<?php

declare(strict_types=1);

namespace App\Dto\User;

class AddressUserTicketSoftDto
{
    public function __construct(
        public string $id,
        public string $StreetAddress,
        public string $email,
        public string $phone,
        public string $phone2,
        public string $mobilePhone,
        public string $mobilePhone2,
    ) {

    }
}
