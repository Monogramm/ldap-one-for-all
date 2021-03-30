<?php

namespace App\Tests\Service\Paypal\Checkout\Object;

use App\Service\Paypal\Checkout\Request\Object\Context;
use PHPUnit\Framework\TestCase;

class ContextUnitTest extends TestCase
{
    public function testContext()
    {
        $context = (new Context())
            ->setCancelUrl('paypal/cancel')
            ->setLandingPage('landingPage')
            ->setLocale('en')
            ->setReturnUrl('paypal/return')
            ->setShippingPreference('shipping')
            ->setUserAction('action');

        $this->assertNotNull($context->getCancelUrl());
        $this->assertEquals('paypal/cancel', $context->getCancelUrl());
        $this->assertNotNull($context->getLandingPage());
        $this->assertEquals('landingPage', $context->getLandingPage());
        $this->assertNotNull($context->getLocale());
        $this->assertEquals('en', $context->getLocale());
        $this->assertNotNull($context->getReturnUrl());
        $this->assertEquals('paypal/return', $context->getReturnUrl());
        $this->assertNotNull($context->getShippingPreference());
        $this->assertEquals('shipping', $context->getShippingPreference());
        $this->assertNotNull($context->getUserAction());
        $this->assertEquals('action', $context->getUserAction());

        $this->assertJson(json_encode($context));
    }
}
