<?php

namespace App\Services\Export;


use Carbon\Carbon;

use App\Repositories\Films\FilmCopyRepository;

class BookingReservationService
{

    public function reserveSeats2(array $query):object {
        $client = new \SoapClient (config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            [
                'soap_version' => SOAP_1_2,
                'encoding' => 'UTF-8',
                'trace' => true
            ]);
        return $client->ReserveSeats2($query);
    }



}
