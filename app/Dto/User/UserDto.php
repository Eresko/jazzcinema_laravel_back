<?php

declare(strict_types=1);

namespace App\Dto\User;

class UserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $birthday,
        public bool $gender,
    ) {

    }
}
