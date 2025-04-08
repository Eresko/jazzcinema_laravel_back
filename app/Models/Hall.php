<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель "Зал"
 *
 * @property int         $id                          Идентификатор
 * @property string      $name                        Название зала
 * @property int         $ticket_soft_id              Идентификатор зала в кассовой системе
 * @property int         $structure_id                Идентификатор структуры в кассовой ситеме
 * @property text        $hall_structure              Схема зала
 * @property bool        $is_display_schedule         Ативность зала для отображения расписания
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */

class Hall  extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ticket_soft_id',
        'structure_id',
        'hall_structure',
        'is_display_schedule',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
