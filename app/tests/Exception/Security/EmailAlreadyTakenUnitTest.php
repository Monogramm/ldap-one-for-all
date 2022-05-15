<?php

namespace App\Tests\Exception\Security;

use App\Exception\Security\SecurityQuestionNotFound;
use PHPUnit\Framework\TestCase;

class SecurityQuestionNotFoundUnitTest extends TestCase
{
    public function testException()
    {
        $exception = (new SecurityQuestionNotFound(null));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(1002, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(404, $exception->getStatusCode());
    }
}
