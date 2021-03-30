<?php

namespace App\Tests\Service\Paypal\Checkout\Object;

use App\Service\Paypal\Checkout\Request\Object\Amount;
use App\Service\Paypal\Checkout\Request\Object\Item;
use App\Service\Paypal\Checkout\Request\Object\PurchaseUnit;
use PHPUnit\Framework\TestCase;

class PurchaseUnitUnitTest extends TestCase
{
    public function testPurchase()
    {
        $item = (new Item())
            ->setUnitAmount((new Amount())->setValue('1'));
        $purchase = (new PurchaseUnit())
            ->setItems([$item])
            ->setAmount(new Amount());

        $this->assertNotNull($purchase->getItems());
        $this->assertNotEmpty($purchase->getItems());
        $this->assertEquals($item, $purchase->getItems()[0]);
        $this->assertNotNull($purchase->getAmount());

        $this->assertJson(json_encode($purchase));
    }
}
