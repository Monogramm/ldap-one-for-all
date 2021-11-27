<?php

namespace App\Tests\Exception\User;

use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class EntityValidationExceptionUnitTest extends TestCase
{
    public function testException()
    {
        $entity = [
            'name' => null
        ];
        $error = new ConstraintViolation('Entity name cannot be null', null, [], $entity, 'name', null);
        $errors = new ConstraintViolationList([
            $error
        ]);
        $exception = (new EntityValidationException($errors));

        $this->assertNotNull($exception->getMessage());

        $this->assertNotNull($exception->getCode());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNotNull($exception->getStatusCode());
        $this->assertEquals(403, $exception->getStatusCode());

        $this->assertNotNull($exception->getErrors());
        $this->assertNotEmpty($exception->getErrors());
        $this->assertCount(1, $exception->getErrors());
    }
}
