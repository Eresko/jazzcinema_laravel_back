<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель "Роль"
 *
 * @property int         $id                          Идентификатор
 * @property string      $name                        Название Роли
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */


class Role extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

}
