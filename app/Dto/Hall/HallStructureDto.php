<?php

declare(strict_types=1);

namespace App\Dto\Hall;

class HallStructureDto
{
    public function __construct(
        public string $id,
        public string $id_tiket,
        public array  $scheme
    ) {

    }
}
