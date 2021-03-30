<?php

namespace App\Tests\Exception\User;

use App\Exception\User\EmailAlreadyTaken;
use PHPUnit\Framework\TestCase;

class EmailAlreadyTakenUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new EmailAlreadyTaken(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(1001, $exception->getStatusCode());
        $this->assertNotNull($exception->getHttpErrorCode());
        $this->assertEquals(409, $exception->getHttpErrorCode());
    }
}
