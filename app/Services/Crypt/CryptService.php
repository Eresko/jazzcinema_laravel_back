<?php

namespace App\Services\Crypt;


use Carbon\Carbon;

use App\Repositories\Halls\HallRepository;
use Illuminate\Support\Collection;
use App\Repositories\Films\ScheduleRepository;
use App\Models\Hall;
use App\Dto\Hall\HallStructureDto;
class CryptService
{

    /**
     * @param $plaintext
     * @return string
     */
    public function encode($plaintext):string
    {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, config('services.key_crypt'), $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, config('services.key_crypt'), $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );

    }

    /**
     * @param string|null $ciphertext
     * @return false|string|void|null
     */
    public function decode(string| null $ciphertext) {
        if ($ciphertext == null) {
            return null;
        }
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, config('services.key_crypt'), $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, config('services.key_crypt'), $as_binary=true);
        if (hash_equals($hmac, $calcmac))// timing attack safe comparison
        {
            return $original_plaintext;
        }
    }
}


