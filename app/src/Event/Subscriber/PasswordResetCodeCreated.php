<?php


namespace App\Event\Subscriber;

use App\Entity\User;
use App\Event\PasswordResetCodeCreatedEvent;
use App\Message\EmailNotification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordResetCodeCreated implements EventSubscriberInterface
{
    private $bus;

    private $router;

    private $mailerFrom;

    private $translator;

    public function __construct(
        MessageBusInterface $bus,
        RouterInterface $router,
        TranslatorInterface $translator,
        string $mailerFrom
    ) {
        $this->bus = $bus;
        $this->router = $router;
        $this->mailerFrom = $mailerFrom;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            PasswordResetCodeCreatedEvent::class => 'onPasswordResetCodeCreated'
        ];
    }

    public function onPasswordResetCodeCreated(PasswordResetCodeCreatedEvent $event): void
    {
        $url = $this->generateUrlWithResetCode(
            $event
                ->getPasswordResetCode()
                ->getCode()
        );

        $user = $event->getUser();

        $this->sendEmailWithPasswordResetUrlToUser($user, $url);
    }

    private function sendEmailWithPasswordResetUrlToUser(User $user, string $url): void
    {
        $subject = $this->translate(
            'email.password.reset.subject',
            $user->getLanguage()
        );

        $explanation = $this->translate(
            'email.password.reset.explanation',
            $user->getLanguage()
        );

        $this->bus->dispatch(
            new EmailNotification(
                $user->getEmail(),
                $subject,
                [
                    'subject' => $subject,
                    'explanation' => $explanation,
                    'urlToResetPassword' => $url
                ],
                'password_reset_code'
            ),
            [new AmqpStamp('user-email', AMQP_NOPARAM, [])]
        );
    }

    private function translate(string $message, string $locale): string
    {
        return $this
            ->translator
            ->trans(
                $message,
                [],
                'messages',
                $locale
            );
    }

    private function generateUrlWithResetCode(string $code): string
    {
        return $this->router->generate(
            'password_reset',
            [
                'code' => $code
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
