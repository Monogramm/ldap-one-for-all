<?php

namespace App\Tests\Service;

use App\Service\CodeGenerator;
use PHPUnit\Framework\TestCase;

class CodeGeneratorUnitTest extends TestCase
{
    public function testGenerator()
    {
        $code = (new CodeGenerator())->generate(8);
        $this->assertIsString($code);
        $this->assertMatchesRegularExpression('/[A-Z0-9]+/', $code);
        $this->assertEquals(8, strlen($code));
    }
}
