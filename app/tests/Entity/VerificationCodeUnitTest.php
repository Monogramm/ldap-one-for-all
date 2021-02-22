<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\VerificationCode;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class VerificationCodeUnitTest extends TestCase
{
    public function testVerificationCode()
    {
        $user = (new User())
            ->setUsername('test.code')
            ->setEmail('test.code@yopmail.com')
            ->setLanguage('en');

        $verificationCode = (new VerificationCode())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setCode('ABC0123XY')
            ->setUser($user);

        $this->assertNotNull($verificationCode->getCreatedAt());
        $this->assertNotNull($verificationCode->getUpdatedAt());

        $this->assertNotNull($verificationCode->getCode());
        $this->assertEquals('ABC0123XY', $verificationCode->getCode());
        $this->assertNotNull($verificationCode->getUser());
        $this->assertEquals($user, $verificationCode->getUser());
    }
}
