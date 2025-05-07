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

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $telegram = Telegram::query()->find($id);
        return !empty($telegram) ? $telegram->delete() : false;

    }


    /**
     * @param int $telegram_id
     * @return Telegram|null
     */
    public function create(int $telegram_id): Telegram | null
    {
        if (Telegram::query()->where('telegram_id', $telegram_id)->exists()) {
            return null;
        }
        return Telegram::create(['telegram_id' => $telegram_id]);
    }
}
