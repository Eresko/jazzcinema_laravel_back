<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель "Привелегии пользователя"
 *
 * @property int         $id                             Идентификатор
 * @property int         $specified_reservation_limit    Лимит бронирования на сеанс
 * @property int         $specified_sales_limit          Лимит продажи на сеанс
 * @property boolean     $sales_allowed                  Флаг разреения продажи пользователю
 * @property Carbon|null $created_at                     Дата создания
 * @property Carbon|null $updated_at                     Дата обновления
 */

class UserPrivilege  extends Model
{
    protected $table = 'user_privileges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'specified_sales_limit',
        'specified_reservation_limit',
        'sales_allowed',
    ];
}
