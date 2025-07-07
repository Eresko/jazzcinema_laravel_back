<?php

declare(strict_types=1);

namespace App\Enums\Booking;

use App\Enums\BaseEnum;

/**
 * Status брони
 *
 * @method static NO_PAID
 * @method static PAID
 * @method static RETURN
 */
class PaidStatus extends BaseEnum
{
    /** Статус  Ожидание */
    const  NO_PAID = 'NO_PAID';

    /** Статус  Ожидание */
    const  PAID = 'PAID';

    const  RETURN = 'RETURN';



    const ALL_STATUS = ['NO_PAID','PAID','RETURN'];
}
