<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
/**
 * Модель "Карта"
 *
 * @property int         $id                          Идентификатор
 * @property string      $name                        Название файла
 * @property string      $type                        Тип файла или класса
 * @property int         $type_id                     Идентификатор типа файла
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */
class File extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'type_id',
    ];

}
