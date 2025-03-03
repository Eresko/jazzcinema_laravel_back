<?php

declare(strict_types=1);

namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class FilmCopy extends Model
{

   


    protected $table = 'film_copies';

    protected $fillable = [
        'name',
        'actors',
        'banners',
        'producers',
        'country',
        'genre',
        'duration',
        'url',
        'start_date',
        'end_date',
        'film_stills',
        'ticket_soft_id',
        'disabled',
        'age',
        'full_age',
        'date_description',
        'update_description',
        'description',
        'date_poster',
        'update_poster',
        'publication',
        'directors',
        'ps',
        'retro',
        'not_only_jazz',
        'external_film_copy_id',
        'rating',
        'video',
        'rating_world',
        'rating_ex',
        'memorandum',
    ];


}

