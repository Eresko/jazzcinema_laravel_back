<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Telegram  extends Model
{
    protected $table = 'telegrams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'telegram_id',
    ];
}
