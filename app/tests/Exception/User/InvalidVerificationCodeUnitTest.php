<?php

namespace App\Tests\Exception\User;

use App\Exception\User\InvalidVerificationCode;
use PHPUnit\Framework\TestCase;

class InvalidVerificationCodeUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new InvalidVerificationCode(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(1004, $exception->getStatusCode());
        $this->assertNotNull($exception->getHttpErrorCode());
        $this->assertEquals(400, $exception->getHttpErrorCode());
    }
}
