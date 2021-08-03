<?php

namespace App\Tests\Exception\User;

use App\Exception\User\UsernameAlreadyTaken;
use PHPUnit\Framework\TestCase;

class UsernameAlreadyTakenUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new UsernameAlreadyTaken(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1002, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(409, $exception->getStatusCode());
    }
}
