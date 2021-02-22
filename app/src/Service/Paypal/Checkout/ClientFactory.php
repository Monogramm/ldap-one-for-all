<?php


namespace App\Service\Paypal\Checkout;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class ClientFactory
{
    public const SANDBOX = 'sandbox';
    public const PROD = 'prod';

    public function create(
        string $env,
        string $clientId,
        string $secret
    ): PayPalHttpClient {
        switch ($env) {
            case self::SANDBOX:
                $environment = new SandboxEnvironment($clientId, $secret);
                break;

            case self::PROD:
                $environment = new ProductionEnvironment($clientId, $secret);
                break;

            default:
                throw new \RuntimeException("Invalid PayPal environment '$env'");
                break;
        }

        return new PayPalHttpClient($environment);
    }
}
