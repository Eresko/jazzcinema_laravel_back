<?php
declare(strict_types=1);

namespace App\Dto\User;

class PrivilegeDto
{

    public function __construct(
        public int  $specifiedReservationLimit,
        public int  $spentArmorLimit,
        public int  $lostSalesLimit = 0,
        public int  $specifiedSalesLimit = 0,
        public bool $salesAllowed = false,
    )
    {

    }
}