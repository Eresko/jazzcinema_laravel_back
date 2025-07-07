<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Модель "Бронирование"
 *
 * @property int         $id                          Идентификатор
 * @property int         $user_id                     Идентификатор пользователя
 * @property int         $transaction_id              Идентификатор платежа
 * @property int         $reservation_id              Идентификатор брованирования кассовой системы
 * @property int         $reservation_number          Номер бронирования кассовой системы
 * @property int         $external_performance_id     Идентификатор сеанса кассовой системы
 * @property Carbon      $data                        Дата продажи
 * @property Json        $seats                       Места продажи
 * @property Carbon|null $created_at                  Дата создания
 * @property Carbon|null $updated_at                  Дата обновления
 */

class Sale extends Model
{


    protected $table = 'sales';


    protected $fillable = [
        'user_id',
        'transaction_id',
        'reservation_id',
        'reservation_number',
        'external_performance_id',
        'structure_element_id',
        'payment_status',
        'repayment_status',
        'date',
        'qr',
        'seats',
    ];

    protected $casts = [
        'seats' => 'json'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
