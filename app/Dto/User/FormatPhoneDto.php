<?php

declare(strict_types=1);

namespace App\Dto\User;

class FormatPhoneDto
{
    public function __construct(
        public string $withAPlus,
        public string $withEight,
        public string $withSeven,
    ) {

    }

    public function toArray()
    {
        return  ['+7' => $this->withAPlus, '8' => $this->withEight, '7' => $this->withSeven];
    }
}
