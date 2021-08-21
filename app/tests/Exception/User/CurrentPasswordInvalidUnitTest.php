<?php

namespace App\Tests\Exception\User;

use App\Exception\User\CurrentPasswordInvalid;
use PHPUnit\Framework\TestCase;

class CurrentPasswordInvalidUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new CurrentPasswordInvalid(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1003, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(403, $exception->getStatusCode());
    }
}
