<?php

namespace App\Tests\Services\Paypal\Checkout;

use App\Service\Paypal\Checkout\ClientFactory;
use PHPUnit\Framework\TestCase;

class ClientFactoryUnitTest extends TestCase
{
    public function testCreate()
    {
        $client = new ClientFactory();

        $factory = $client->create(ClientFactory::SANDBOX, 'clientId', 'secret');

        $this->assertNotNull($factory);
    }
}
