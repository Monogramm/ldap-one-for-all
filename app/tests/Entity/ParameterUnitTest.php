<?php

namespace App\Tests;

use App\Entity\Parameter;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ParameterUnitTest extends TestCase
{
    public function testParameterString()
    {
        $parameter = (new Parameter())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setName('DUMMY')
            ->setValue('test')
            ->setDescription('Dummy string parameter')
            ->setType('string');

        $this->assertNotNull($parameter->getCreatedAt());
        $this->assertNotNull($parameter->getUpdatedAt());

        $this->assertNotNull($parameter->getName());
        $this->assertEquals('DUMMY', $parameter->getName());
        $this->assertNotNull($parameter->getValue());
        $this->assertEquals('test', $parameter->getValue());

        $this->assertNotNull($parameter->getDescription());
        $this->assertEquals('Dummy string parameter', $parameter->getDescription());

        $this->assertNotNull($parameter->getType());
        $this->assertEquals(Parameter::STRING_TYPE, $parameter->getType());

        $this->assertFalse($parameter->isSecret());
    }

    public function testParameterNumber()
    {
        $parameter = (new Parameter())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setName('DUMMY')
            ->setValue('42')
            ->setDescription('Dummy number parameter')
            ->setType('number');

        $this->assertNotNull($parameter->getCreatedAt());
        $this->assertNotNull($parameter->getUpdatedAt());

        $this->assertNotNull($parameter->getName());
        $this->assertEquals('DUMMY', $parameter->getName());
        $this->assertNotNull($parameter->getValue());
        $this->assertEquals('42', $parameter->getValue());

        $this->assertNotNull($parameter->getDescription());
        $this->assertEquals('Dummy number parameter', $parameter->getDescription());

        $this->assertNotNull($parameter->getType());
        $this->assertEquals(Parameter::NUMBER_TYPE, $parameter->getType());

        $this->assertFalse($parameter->isSecret());
    }

    public function testParameterSecret()
    {
        $parameter = (new Parameter())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setName('DUMMY')
            ->setValue('S&cr37')
            ->setDescription('Dummy secret parameter')
            ->setType('secret');

        $this->assertNotNull($parameter->getCreatedAt());
        $this->assertNotNull($parameter->getUpdatedAt());

        $this->assertNotNull($parameter->getName());
        $this->assertEquals('DUMMY', $parameter->getName());
        $this->assertNotNull($parameter->getValue());
        $this->assertEquals('S&cr37', $parameter->getValue());

        $this->assertNotNull($parameter->getDescription());
        $this->assertEquals('Dummy secret parameter', $parameter->getDescription());

        $this->assertNotNull($parameter->getType());
        $this->assertEquals(Parameter::SECRET_TYPE, $parameter->getType());

        $this->assertTrue($parameter->isSecret());
    }
}
