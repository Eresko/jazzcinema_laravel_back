<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;


/**
 * Модель "Баннеры"
 *
 * @property int         $id             Идентификатор
 * @property string      $name           Название баннера
 * @property Carbon|null $start          Дата старта отображения банера
 * @property Carbon|null $end            Дата окончания отображения банера
 * @property Carbon|null $created_at     Дата создания
 * @property Carbon|null $updated_at     Дата обновления
 */

class Banner extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'banners';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start',
        'end',
    ];

}
