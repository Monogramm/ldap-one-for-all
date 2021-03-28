<?php

namespace App\Tests\Message\Handler;

use App\Message\EmailNotification;
use App\Message\Handler\EmailNotificationHandler;
use App\Service\Mailer\EmailFactory;
use App\Service\Mailer\EmailToSupport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotificationHandlerUnitTest extends TestCase
{
    public function testHandler()
    {
        $emailFactory = $this->createMock(EmailFactory::class);
        $mailer = $this->createMock(MailerInterface::class);

        $emailNotificationHandler = new EmailNotificationHandler($mailer, $emailFactory);

        $emailNotification = new EmailNotification(
            'test@test.com',
            'example@example.com',
            [],
            'template'
        );

        $emailNotificationHandler($emailNotification);

        $this->assertTrue(true);
    }
}
