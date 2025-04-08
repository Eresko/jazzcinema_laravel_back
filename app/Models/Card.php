<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
/**
 * Модель "Карта"
 *
 * @property int         $external_id                 Идентификатор карты кассовой системы
 * @property int         $loyalty_program_id          Идентификатор программы лояльности
 * @property string      $number                      Номер карты лояльности
 * @property int         $secret_code                 Секретный код карты лояльности
 * @property int         $pin_code                    Пин код карты лояльности
 * @property bool        $is_valid                    Дейстует карта или нет
 * @property Carbon|null $issue_date                  Дата до которой действует карта
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */
class Card extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'cards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'loyalty_program_id',
        'number',
        'secret_code',
        'pin_code',
        'is_valid',
        'issue_date',
        'user_id',
    ];

}
