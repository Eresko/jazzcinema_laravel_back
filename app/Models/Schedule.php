<?php

declare(strict_types=1);

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
class Schedule extends Model
{

    use Searchable;
    protected $table = 'schedules';

    protected $fillable = [
        'structure_element_id',
        'start_time',
        'price',
        'start_date',
        'external_film_copy_id',
        'external_performance_id',
        'hall'
    ];
}

