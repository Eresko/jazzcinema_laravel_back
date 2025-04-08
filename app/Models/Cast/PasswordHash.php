<?php

declare(strict_types=1);

namespace App\Models\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;

class PasswordHash implements CastsInboundAttributes
{

    public function set($model, $key, $plaintext, $attributes): string
    {
        
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, config('services.key_crypt'), $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, config('services.key_crypt'), $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }
}
