<?php

namespace App\Repositories\HandBook;

use Carbon\Carbon;
use App\Models\Telegram;
use Illuminate\Support\Collection;
class TelegramRepository
{


    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Telegram::query()->get();

    }
}
