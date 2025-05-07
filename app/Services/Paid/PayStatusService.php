<?php

namespace App\Services\Paid;

class PayStatusService
{
    public function orderStatus(string $orderId)
    {

        $loginPay = new LoginPay();
        $url = 'https://3dsec.sberbank.ru/payment/rest/getOrderStatusExtended.do?';

        $data = [
            'userName' => $loginPay->getLogin() ,
            'password' =>  $loginPay->getPassword(),
            'orderNumber' => $orderId,
        ];
        $data = http_build_query($data);
        $ch = curl_init($url.$data);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, getcwd() . '/usr/www/jazzcinema/api.jazzcinema.ru/www/Cert_CA.pem');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultStr = curl_exec($ch);
        $error =  curl_errno($ch);
        curl_close($ch);

        return json_decode($resultStr);
    }

}
