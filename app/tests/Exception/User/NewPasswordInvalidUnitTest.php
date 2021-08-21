<?php

namespace App\Tests\Exception\User;

use App\Exception\User\NewPasswordInvalid;
use PHPUnit\Framework\TestCase;

class NewPasswordInvalidUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new NewPasswordInvalid(NewPasswordInvalid::MSG_SAME_CURRENT));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1004, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(403, $exception->getStatusCode());
    }
}
