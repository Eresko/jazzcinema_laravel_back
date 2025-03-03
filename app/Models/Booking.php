<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{


    protected $table = 'bookings';


    protected $fillable = [
        'user_id',
        'reservation_id',
        'reservation_number',
        'external_performance_id',
        'structure_element_id',
        'date',
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
