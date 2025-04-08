<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Cast\PasswordHash;

/**
 * Модель "Пользователь"
 *
 * @property int         $id                          Идентификатор
 * @property string      $name                        Имя пользователя
 * @property string      $email                       Электронная почта пользователя
 * @property string      $phone                       Номер телефона пользователя
 * @property string      $password                    Пароль пользователя в hash_hmac
 * @property int         $external_id                 Идентификатор пользователя из кассовой системы
 * @property int         $role_id                     Идентификатор роли
 * @property bool        $gender                      Пол пользователя
 * @property Carbon|null $birthday                    Дата рождения пользователя
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'external_id',
        'role_id',
        'gender',
        'birthday',
    ];


    protected  $casts = [
        'password' => PasswordHash::class,
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

    

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
