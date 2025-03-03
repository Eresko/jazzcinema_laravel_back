<?php

declare(strict_types=1);

namespace App\Dto\User;

class CardResponseDto
{
    public function __construct(
        public CardUserDto | array $Jazzcinema_Club = [],
        public CardUserDto | array $Kinoshka = [],
        public CardUserDto | array $stud = []
    ) {
    }
}
