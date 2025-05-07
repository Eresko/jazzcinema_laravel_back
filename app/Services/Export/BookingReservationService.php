<?php

namespace App\Services\Export;

use Carbon\Carbon;
use App\Repositories\Films\FilmCopyRepository;

class BookingReservationService
{
    public function reserveSeats2(array $query): object
    {
        $client = new \SoapClient(
            config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            [
                'soap_version' => SOAP_1_2,
                'encoding' => 'UTF-8',
                'trace' => true
            ]
        );
        return $client->ReserveSeats2($query);
    }

    public function ReservationPayed(int $reservationId, int $sum)
    {
        $client = new \SoapClient(
            config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            [
                'soap_version' => SOAP_1_2,
                'encoding' => 'UTF-8',
                'trace' => true
            ]
        );
        $query = [
        'cinemaId' => 1, //id
        'reservationId' => $reservationId, // id брони
        'internalId' => $reservationId, // id брони
        'contractId' => 36626724,
        'sum' => $sum, //сумма оплаты
        ];
        return $client->ReservationPayed2($query);
    }

    public function sellReservation(int $reservationId, int $sum)
    {
        $client = new \SoapClient(
            config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            [
                'soap_version' => SOAP_1_2,
                'encoding' => 'UTF-8',
                'trace' => true
            ]
        );
        $query = [
            'cinemaId' => 1, //id
            'reservationId' => $reservationId, // id брони
            'extraServiceId' => 1, // id брони
            'internalId' => $reservationId.'3', // id брони
        ];
        return $client->sellReservation($query);
    }
    public function confirmReservation(int $reservationId)
    {
        $client = new \SoapClient(
            config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            [
                'soap_version' => SOAP_1_2,
                'encoding' => 'UTF-8',
                'trace' => true
            ]
        );
        $query = [
            'cinemaId' => 1, //id
            'reservationId' => $reservationId, // id брони
            'extraServiceId' => $reservationId, // id брони
            'internalId' => $reservationId, // id брони

        ];

        return $client->ConfirmReservation($query);

    }
}
