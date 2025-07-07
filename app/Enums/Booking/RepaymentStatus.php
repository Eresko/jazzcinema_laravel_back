<?php

declare(strict_types=1);

namespace App\Enums\Booking;

use App\Enums\BaseEnum;

/**
 * Status брони
 *
 * @method static PENDING
 * @method static ACCEPT
 */
class RepaymentStatus extends BaseEnum
{
    /** Статус  Ожидание */
    const  PENDING = 'PENDING';

    /** Статус  Ожидание */
    const  ACCEPT = 'ACCEPT';



    const ALL_STATUS = ['PENDING','ACCEPT'];
}
