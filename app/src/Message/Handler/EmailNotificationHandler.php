<?php


namespace App\Message\Handler;

use App\Message\EmailNotification;
use App\Service\Mailer\EmailFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailNotificationHandler implements MessageHandlerInterface
{
    private $mailer;

    private $factory;

    public function __construct(
        MailerInterface $mailer,
        EmailFactory $factory
    ) {
        $this->mailer = $mailer;
        $this->factory = $factory;
    }

    public function __invoke(EmailNotification $emailNotification)
    {
        $email = $this
            ->factory
            ->createEmailFromMessage($emailNotification);

        $this->mailer->send($email);
    }
}
