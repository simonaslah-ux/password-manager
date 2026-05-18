<?php

class Encryptor
{
    private string $method = 'AES-256-CBC';

    private function getKey(string $password): string
    {
        return hash('sha256', $password, true);
    }

    public function encrypt(string $data, string $password): string
    {
        $key = $this->getKey($password);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));

        $encrypted = openssl_encrypt($data, $this->method, $key, 0, $iv);

        return base64_encode($iv . $encrypted);
    }

    public function decrypt(string $encryptedData, string $password): string|false
    {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($this->method);

        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        $key = $this->getKey($password);

        return openssl_decrypt($encrypted, $this->method, $key, 0, $iv);
    }
}