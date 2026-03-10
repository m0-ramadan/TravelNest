<?php

namespace App\Services;

class LikeCardService
{
    public function decryptSerial(string $encryptedTxt): string|null
    {
        $secretKey = config('services.like4app.secret_key');
        $secretIv = config('services.like4app.secret_iv');
        $encryptMethod = 'AES-256-CBC';

        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretIv), 0, 16);

        $decrypted = openssl_decrypt(
            base64_decode($encryptedTxt),
            $encryptMethod,
            $key,
            0,
            $iv
        );

        return $decrypted !== false ? $decrypted : null;
    }
}
