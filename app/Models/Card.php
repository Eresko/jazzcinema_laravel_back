<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

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
