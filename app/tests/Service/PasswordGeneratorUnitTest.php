<?php

namespace App\Tests\Service;

use App\Service\PasswordGenerator;
use PHPUnit\Framework\TestCase;

class PasswordGeneratorUnitTest extends TestCase
{
    public function testGenerator()
    {
        $code = (new PasswordGenerator())->generate(8);
        $this->assertIsString($code);
        $this->assertMatchesRegularExpression('/[A-Za-z0-9]|[+\-*@&#]/m', $code);
        $this->assertEquals(8, strlen($code));
    }
}
