<?php

namespace App\Tests\Exception\User;

use App\Exception\User\ConfirmPasswordInvalid;
use PHPUnit\Framework\TestCase;

class ConfirmPasswordInvalidUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new ConfirmPasswordInvalid(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1005, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(403, $exception->getStatusCode());
    }
}
