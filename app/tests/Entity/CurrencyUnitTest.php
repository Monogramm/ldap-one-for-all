<?php

namespace App\Tests\Entity;

use App\Entity\Currency;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CurrencyUnitTest extends TestCase
{
    public function testCurrency()
    {
        $currency = (new Currency())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setName('EURO')
            ->setIsoCode('EUR');

        $this->assertNotNull($currency->getCreatedAt());
        $this->assertNotNull($currency->getUpdatedAt());

        $this->assertNotNull($currency->getName());
        $this->assertEquals('EURO', $currency->getName());
        $this->assertNotNull($currency->getIsoCode());
        $this->assertEquals('EUR', $currency->getIsoCode());
    }
}
