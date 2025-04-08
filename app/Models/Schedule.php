<?php

declare(strict_types=1);

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Модель "Расписание"
 *
 * @property int         $id                          Идентификатор
 * @property int         $structure_element_id        Идентификатор структуры (зала) из кассовой системы
 * @property Carbon      $start_time                  Время старта мероприятия
 * @property int         $price                       Стоимость мероприятия
 * @property Carbon      $start_date                  Дата старта мероприятия
 * @property int         $external_film_copy_id       Идентификатор фильмокопии из кассовой системы
 * @property int         $external_performance_id     Идентификатор сеанса из кассовой системы
 * @property string      $hall                        Название зала
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */

class Schedule extends Model
{

    use Searchable;
    protected $table = 'schedules';

    protected $fillable = [
        'structure_element_id',
        'start_time',
        'price',
        'start_date',
        'external_film_copy_id',
        'external_performance_id',
        'hall'
    ];
}

