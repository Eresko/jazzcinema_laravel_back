<?php

declare(strict_types=1);

namespace App\Dto\FilmCopy;

class FilmCopyDto
{
    public function __construct(
        public string $startDate,
        public string $disabled,
        public string | null $actors,
        public string | null $age,
        public string | null $country,
        public string | null $description,
        public string | null $genre,
        public string | null $memorandum,
        public string | null $rating,
        public string | null $producers,
        public bool $notOnlyJazz,
        public bool $ps,
        public bool $publication,
        public bool $retro,
        public string | null $directors,
    ) {

    }

    public function toArray(): array
    {
        return [
            'start_date' => $this->startDate,
            'disabled' => $this->disabled,
            'actors' => $this->actors,
            'age' => $this->age,
            'country' => $this->country,
            'description' => $this->description,
            'genre' => $this->genre,
            'memorandum' => $this->memorandum,
            'rating' => $this->rating,
            'producers' => $this->producers,
            'not_only_jazz' => $this->notOnlyJazz,
            'ps' => $this->ps,
            'publication' => $this->publication,
            'retro' => $this->retro,
            'directors' => $this->directors,
        ];
    }


}
