<?php

namespace App\Tests;

use App\Entity\PasswordResetCode;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PasswordResetCodeUnitTest extends TestCase
{
    public function testPasswordResetCode()
    {
        $passwordResetCode = (new PasswordResetCode())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setCode('ABC0123XY');

        $this->assertNotNull($passwordResetCode->getCreatedAt());
        $this->assertNotNull($passwordResetCode->getUpdatedAt());

        $this->assertNotNull($passwordResetCode->getCode());
        $this->assertEquals('ABC0123XY', $passwordResetCode->getCode());
    }
}
