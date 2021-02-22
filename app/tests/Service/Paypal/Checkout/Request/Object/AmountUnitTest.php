<?php

namespace App\Tests;

use App\Service\Paypal\Checkout\Request\Object\Amount;
use PHPUnit\Framework\TestCase;

class AmountUnitTest extends TestCase
{
    public function testAmount()
    {
        $amount = (new Amount())
            ->setCurrencyCode('EUR')
            ->setValue('23.0');

        $this->assertNotNull($amount->getCurrencyCode());
        $this->assertEquals('EUR', $amount->getCurrencyCode());
        $this->assertNotNull($amount->getValue());
        $this->assertEquals('23.0', $amount->getValue());

        $this->assertJson(json_encode($amount));
    }
}
