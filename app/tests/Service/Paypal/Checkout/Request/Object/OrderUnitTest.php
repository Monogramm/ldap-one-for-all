<?php

namespace App\Tests;

use App\Service\Paypal\Checkout\Request\Object\Context;
use App\Service\Paypal\Checkout\Request\Object\Order;
use PHPUnit\Framework\TestCase;

class OrderUnitTest extends TestCase
{
    public function testOrder()
    {
        $order = (new Order())
            ->setContext(new Context())
            ->setIntent('intent')
            ->setPurchaseUnits([]);

        $this->assertNotNull($order->getContext());
        $this->assertNotNull($order->getIntent());
        $this->assertEquals('intent', $order->getIntent());
        $this->assertNotNull($order->getPurchaseUnits());
        $this->assertEmpty($order->getPurchaseUnits());

        $this->assertJson(json_encode($order));
    }
}
