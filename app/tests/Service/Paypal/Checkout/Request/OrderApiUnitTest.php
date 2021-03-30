<?php

namespace App\Tests\Service\Paypal\Checkout;

use App\Service\Paypal\Checkout\Request\Object\Order;
use App\Service\Paypal\Checkout\Request\OrderApi;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpResponse;
use PHPUnit\Framework\TestCase;

class OrderApiUnitTest extends TestCase
{
    public function testCreate()
    {
        $client =  $this->createMock(PayPalHttpClient::class);
        $httpResponse = $this->createMock(HttpResponse::class);
        $client
            ->method('execute')
            ->willReturn($httpResponse);

        $orderApi = new OrderApi($client);

        $order = new Order();
        $order->setPurchaseUnits([]);

        $response = $orderApi->create($order);

        $this->assertNotNull($response);
    }

    public function testCapture()
    {
        $client =  $this->createMock(PayPalHttpClient::class);
        $httpResponse = $this->createMock(HttpResponse::class);
        $client
            ->method('execute')
            ->willReturn($httpResponse);

        $orderApi = new OrderApi($client);

        $response = $orderApi->capture('orderId');

        $this->assertNotNull($response);
    }

    public function testGetDetails()
    {
        $client =  $this->createMock(PayPalHttpClient::class);
        $httpResponse = $this->createMock(HttpResponse::class);
        $client
            ->method('execute')
            ->willReturn($httpResponse);

        $orderApi = new OrderApi($client);

        $response = $orderApi->getDetails('orderId');

        $this->assertNotNull($response);
    }
}
