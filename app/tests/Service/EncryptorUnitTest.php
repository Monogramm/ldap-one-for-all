<?php

namespace App\Tests\Service;

use App\Service\Encryptor;
use PHPUnit\Framework\TestCase;

class EncryptorUnitTest extends TestCase
{
    public function testTextEnryption()
    {
        $testText = 'test';
        $encryptor = new Encryptor('12345678901234567890123456789012');

        $encrypted = $encryptor->encryptText($testText);

        $this->assertIsString($encrypted);
        $this->assertNotEquals($testText, $encrypted);

        $decrypted = $encryptor->decryptText($encrypted);

        $this->assertEquals($decrypted, $testText);
    }

    public function testArrayEnryption()
    {
        $testArray = ['test' => 'test'];
        $encryptor = new Encryptor('12345678901234567890123456789012');

        $encrypted = $encryptor->encrypt($testArray);

        $this->assertIsString($encrypted);
        $this->assertNotEquals($testArray, $encrypted);

        $decrypted = $encryptor->decrypt($encrypted);

        $this->assertEquals($decrypted, $testArray);
    }
}
