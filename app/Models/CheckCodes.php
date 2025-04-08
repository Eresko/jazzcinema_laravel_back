<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
/**
 * Модель "Проверка кода входа"
 *
 * @property int         $id                          Идентификатор
 * @property int         $user_id                     Идентификатор пользователя
 * @property string      $phone                       Номер телефона
 * @property string      $code                        Секретный код подтверждения
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */
class CheckCodes extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'check_codes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'code',
    ];

}