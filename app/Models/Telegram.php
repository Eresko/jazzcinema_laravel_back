<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель "Телеграм"
 *
 * @property int         $id                          Идентификатор
 * @property int         $telegram_id                 Идентификатор телеграма
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */

class Telegram  extends Model
{
    protected $table = 'telegrams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'telegram_id',
    ];
}
