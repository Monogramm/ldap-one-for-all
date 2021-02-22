<?php

namespace App\Tests;

use App\Entity\ApiToken;
use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ApiTokenUnitTest extends TestCase
{
    public function testApiToken()
    {
        $apiToken = (new ApiToken())
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
            ->setToken('1234567890')
            ->setExpiredAt(Carbon::now());

        $this->assertNotNull($apiToken->getCreatedAt());
        $this->assertNotNull($apiToken->getUpdatedAt());

        $this->assertNotNull($apiToken->getToken());
        $this->assertEquals('1234567890', $apiToken->getToken());
        $this->assertNotNull($apiToken->getExpiredAt());

        sleep(1);
        $this->assertTrue($apiToken->isNowExpired());
        $this->assertTrue($apiToken->isExpiredAt(Carbon::now()));
    }
}
