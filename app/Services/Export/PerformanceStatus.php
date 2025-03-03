<?php

namespace App\Services\Export;


use Carbon\Carbon;

use App\Repositories\Films\FilmCopyRepository;

class PerformanceStatus
{

    public function getStatusPerformance(int $performanceId,int $hallId):array {
        $client = new \SoapClient (config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            [
                'soap_version' => SOAP_1_2,
                'encoding' => 'UTF-8',
                'trace' => true
            ]);
        $hall = $client->HallSeatStatus( [ 'cinemaId' => 1,'actionId' => $performanceId,'containerId' => $hallId]);
        return ($hall->HallSeatStatusResult->seat3) ? $hall->HallSeatStatusResult->seat3 : [];
    }



}
