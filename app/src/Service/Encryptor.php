<?php


namespace App\Service;

class Encryptor
{
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(array $data): string
    {
        $json = json_encode($data);

        return $this->encryptText($json);
    }

    public function decrypt(string $encryptedText): array
    {
        $json = $this->decryptText($encryptedText);

        return json_decode($json, true);
    }

    /**
     * Encrypt text.
     *
     * @param string $text text to encrypt.
     *
     * @return string
     */
    public function encryptText(string $text): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($text, $nonce, $this->key);

        return base64_encode($nonce . $ciphertext);
    }

    /**
     * Decrypt an encrypted text message.
     *
     * @param string $encryptedText text to decrypt.
     *
     * @return string
     */
    public function decryptText(string $encryptedText)
    {
        $encryptedText = base64_decode($encryptedText);
        $nonce = mb_substr($encryptedText, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($encryptedText, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        if (!$ciphertext) {
            return '';
        }

        return sodium_crypto_secretbox_open($ciphertext, $nonce, $this->key);
    }
}
