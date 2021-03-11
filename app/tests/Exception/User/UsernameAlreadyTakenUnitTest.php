<?php

namespace App\Tests;

use App\Exception\User\UsernameAlreadyTaken;
use PHPUnit\Framework\TestCase;

class UsernameAlreadyTakenUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new UsernameAlreadyTaken(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(1002, $exception->getStatusCode());
        $this->assertNotNull($exception->getHttpErrorCode());
        $this->assertEquals(409, $exception->getHttpErrorCode());
    }
}
