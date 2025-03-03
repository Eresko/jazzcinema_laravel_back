<?php

declare(strict_types=1);

namespace App\Enums\Card;

use App\Enums\BaseEnum;

/**
 * Type loyalty program
 *
 * @method static JAZZCINEMA_CLUB
 * @method static KIDS
 * @method static STUDENT
 */
class LoyaltyProgram extends BaseEnum
{
    /** Стандартная карта jazzcinema */
    const  JAZZCINEMA_CLUB= [
        'id' => 10161366,
        'name' => 'Карта лояльности Jazz Cinema'
    ];
    /** Role "Brand Role" */
    const KIDS = [
        'id' => 28740293,
        'name' => 'Детская карта КиноШка'
    ];
    /** Role "Streamer Role" */
    const STUDENT = [
        'id' => 31789471,
        'name' => 'Студенческий билет'
    ];

    const ALL_PROGRAM = [
        [
            'id' => 10161366,
            'name' => 'Карта лояльности Jazz Cinema',
            'code' => "Jazzcinema_Club"
        ],
        [
            'id' => 28740293,
            'name' => 'Детская карта КиноШка',
            'code' => 'Kinoshka',
        ],
        [
            'id' => 31789471,
            'name' => 'Студенческий билет',
            'code' => 'stud'
        ]
    ];
}
