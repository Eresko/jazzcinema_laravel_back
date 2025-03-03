<?php

declare(strict_types=1);

namespace App\Dto\User;

class MessageDto
{
    public function __construct(
        public string | null $email,
        public string $message,
        public string $format,
        public string $theme,
        public UploadedFile|null  $photo,
    ) {

    }
}
