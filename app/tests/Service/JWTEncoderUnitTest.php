<?php

namespace App\Tests;

use App\Service\JWTEncoder;
use PHPUnit\Framework\TestCase;

class JWTEncoderUnitTest extends TestCase
{
    public function testEncoder()
    {
        $testPayload = ['test' => 'test'];

        $encoder = new JWTEncoder();

        $encoded = $encoder->encode($testPayload);

        $this->assertIsString($encoded);
        $this->assertNotEquals($testPayload, $encoded);

        $decoded = $encoder->decode($encoded);
        unset($decoded['iat']);
        $this->assertEquals($testPayload, $decoded);
    }
}
