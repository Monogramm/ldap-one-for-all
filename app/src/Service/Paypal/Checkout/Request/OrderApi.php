<?php


namespace App\Service\Paypal\Checkout\Request;

use App\Service\Paypal\Checkout\Request\Object\Order;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpResponse;

class OrderApi
{
    public $client;

    public function __construct(PayPalHttpClient $client)
    {
        $this->client = $client;
    }

    public function create(Order $order): HttpResponse
    {
        $request = new OrdersCreateRequest();
        $request->body = $order->jsonSerialize();
        $request->prefer('return=representation');

        return $this->client->execute($request);
    }

    public function capture(string $orderId): HttpResponse
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');

        return $this->client->execute($request);
    }

    public function getDetails(string $orderId): HttpResponse
    {
        $request = new OrdersGetRequest($orderId);

        return $this->client->execute($request);
    }
}
