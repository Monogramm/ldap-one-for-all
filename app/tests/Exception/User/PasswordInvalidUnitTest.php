<?php

namespace App\Tests\Exception\User;

use App\Exception\User\PasswordInvalid;
use PHPUnit\Framework\TestCase;

class PasswordInvalidUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new PasswordInvalid(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1003, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(403, $exception->getStatusCode());
    }
}
