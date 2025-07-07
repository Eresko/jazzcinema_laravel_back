<?php

declare(strict_types=1);

namespace App\Dto\User;

class UpdateStaffDto
{
    public function __construct(
        public string $name,
        public string | null $password,
        public string $email,
    ) {

    }

    public function toArray() {
        $massive = [];
        foreach ($this as $key => $item) {
            if (!empty($item)) {
                $massive[$key] = $item;
            }
        }
        return $massive;
    }
}
