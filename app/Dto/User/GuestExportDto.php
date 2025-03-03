<?php

declare(strict_types=1);

namespace App\Dto\User;

class GuestExportDto
{
    public function __construct(
        public string|null $name,
        public string|null $email,
        public string      $phone,
        public int|null    $externalId,
        public bool        $gender,
        public string|null $birthday,
        public array       $cards = []
    ) {

    }
}
