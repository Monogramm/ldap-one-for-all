<?php

namespace App\Tests\Exception\Security;

use App\Exception\Security\UsernameNotFound;
use PHPUnit\Framework\TestCase;

class UsernameNotFoundUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new UsernameNotFound(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1001, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(404, $exception->getStatusCode());
    }
}
