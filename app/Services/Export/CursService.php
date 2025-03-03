<?php

namespace App\Services\Export;


use Carbon\Carbon;

use App\Repositories\Films\FilmCopyRepository;

class CursService
{

    public function __construct()
    {
    }

    public function get(string $url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
        return $out;
    }


    public function post(string $url,array $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultStr = curl_exec($ch);
        curl_close($ch);
        return $resultStr;
    }

}