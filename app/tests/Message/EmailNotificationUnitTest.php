<?php

namespace App\Tests;

use App\Message\EmailNotification;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class EmailNotificationUnitTest extends TestCase
{
    public function testEmailNotification()
    {
        $emailNotification = new EmailNotification(
            'dest@yopmail.com',
            'Test Email',
            [],
            'base.email.html.twig',
            'from@yopmail.com'
        );

        $this->assertNotNull($emailNotification->sender());
        $this->assertEquals('from@yopmail.com', $emailNotification->sender());

        $this->assertNotNull($emailNotification->recipient());
        $this->assertEquals('dest@yopmail.com', $emailNotification->recipient());

        $this->assertNotNull($emailNotification->subject());
        $this->assertEquals('Test Email', $emailNotification->subject());

        $this->assertNotNull($emailNotification->payload());
        $this->assertEquals([], $emailNotification->payload());

        $this->assertNotNull($emailNotification->template());
        $this->assertEquals('base.email.html.twig', $emailNotification->template());
    }
}
