<?php

namespace App\Services\Export;


use Carbon\Carbon;
use App\Dto\User\AddressUserTicketSoftDto;
use App\Dto\User\CardUserTicketSoftDto;
use App\Dto\User\CustomerUserTicketSoftDto;
use App\Repositories\Users\GuestRepository;

class UserTicketSoftService
{

    public function __construct(
        protected GuestRepository $guestRepository,
        protected CursService     $cursService
    )
    {
    }

    /**
     * @param $phone
     * @return AddressUserTicketSoftDto|null
     */
    public function getAddress($phone):AddressUserTicketSoftDto | null//Получение данных по номеру телефона
    {

        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'phone/',[  'phone' => $phone]);
        $address = null;
        foreach (json_decode($resultStr) as $row) {
            $address  = new AddressUserTicketSoftDto(
                $row->ID,
                $row->StreetAddress,
                $row->EMail,
                $row->Phone,
                $row->Phone2,
                $row->mobilePhone,
                $row->mobilePhone2
            );
        }
        return $address;
    }


    /**
     * @param int $id
     * @return array
     */
    public function getCard(int $id): array
    { //Получение карт лояльности по id пользователя

        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'card/', ['id' => $id]);
        $cards = [];
        $temporary = [];
        foreach (json_decode($resultStr) as $row) {
            $temporary[] = $row;
        }
        foreach ($temporary as $itemTemporary) {
            $cards[] = new CardUserTicketSoftDto(
                $itemTemporary->ID,
                $itemTemporary->LoyaltyProgramID,
                $itemTemporary->CardNumber,
                $itemTemporary->CardSecret,
                $itemTemporary->PINCode,
                $itemTemporary->IsValid,
                $itemTemporary->IssueDate
            );

        }
        return $cards;
    }

    /**
     * @param $idAddress
     * @return CustomerUserTicketSoftDto|null
     */

    public function getUserFromCustomer($idAddress):CustomerUserTicketSoftDto | null
    {//получаем пользовате по id адреса
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'customer/', ['id' => $idAddress]);
        $user = null;
        foreach (json_decode($resultStr) as $row) {
            $user = new CustomerUserTicketSoftDto(
                $row->ID,
                $row->RootCustomerID,
                $row->AddressID,
                $row->FirstName,
                $row->MiddleName,
                $row->LastName,
                $row->Gender,
                $row->BirthDate
            );
        }
        return $user;
    }

    /**
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function updateFirstName(int $id,string $name):bool//Получение данных по номеру телефона
    {
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'update-name', [  'id' => $id, 'name' => $name]);
        return true;
    }

    /**
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function updateLastName(int $id,string $name):bool//Получение данных по номеру телефона
    {
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'update-last-name', [  'id' => $id, 'name' => $name]);
        return true;
    }


    /**
     * @param int $id
     * @param string $date
     * @return bool
     */
    public function updateBirthDate(int $id,string $date):bool//Получение данных по номеру телефона
    {
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'update-last-name', [  'id' => $id, 'date' => $date]);
        return true;
    }

    /**
     * @param string $phone
     * @return array
     */
    public function createUser(string $phone) {
        $client = new \SoapClient (config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            ['soap_version' => SOAP_1_2, 'encoding' => 'UTF-8', 'trace' => true]);

        $query =[
            'cinemaId'=> 1,
            'email'=> $phone.'@mail.ru',
            'password' => '',
            'lastName'=>'Пользователь приложения  '.$phone,
            'firstName' => '',
            'middleName'=> '',
            'phone'=> $phone,
            'loyaltyProgramId' => 10161366];


        $queryNewClient = $client->AddCustomer($query);
        return (array)((array)$queryNewClient)['AddCustomerResult'];

    }

    /**
     * @param string $number
     * @return null
     */
    public function getCardB(string $number) {
        $client = new \SoapClient (config('services.ticket_soft_url')."webpart-all/services/WebPart?WSDL",
            ['soap_version' => SOAP_1_2, 'encoding' => 'UTF-8', 'trace' => true]);
        $queryCard = null;
        try {
            $queryCard = $client->GetSellCounters([
                'cinemaId'=> 1,
                'cardNumber'=> $number
            ]);

        }
        catch (Exception $e) {

        }
        return $queryCard;
    }
}