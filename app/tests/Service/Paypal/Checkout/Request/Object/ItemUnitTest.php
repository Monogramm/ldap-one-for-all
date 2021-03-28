<?php

namespace App\Tests\Service\Paypal\Checkout\Object;

use App\Service\Paypal\Checkout\Request\Object\Amount;
use App\Service\Paypal\Checkout\Request\Object\Item;
use PHPUnit\Framework\TestCase;

class ItemUnitTest extends TestCase
{
    public function testItem()
    {
        $item = (new Item())
            ->setName('name')
            ->setQuantity(1)
            ->setUnitAmount(new Amount());

        $this->assertNotNull($item->getName());
        $this->assertEquals('name', $item->getName());
        $this->assertNotNull($item->getQuantity());
        $this->assertEquals(1, $item->getQuantity());
        $this->assertNotNull($item->getUnitAmount());

        $this->assertJson(json_encode($item));
    }
}
