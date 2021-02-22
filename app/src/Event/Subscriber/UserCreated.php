<?php


namespace App\Event\Subscriber;

use App\Entity\VerificationCode;
use App\Event\UserCreatedEvent;
use App\Message\EmailNotification;
use App\Service\CodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserCreated implements EventSubscriberInterface
{
    private $bus;

    private $em;

    private $codeGenerator;

    private $mailerFrom;

    private $translator;

    public function __construct(
        MessageBusInterface $bus,
        EntityManagerInterface $em,
        CodeGenerator $codeGenerator,
        TranslatorInterface $translator,
        string $mailerFrom
    ) {
        $this->bus = $bus;
        $this->em = $em;
        $this->codeGenerator = $codeGenerator;
        $this->mailerFrom = $mailerFrom;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::class => 'onUserCreated'
        ];
    }

    public function onUserCreated(UserCreatedEvent $event): void
    {
        $code = new VerificationCode();
        $code->setCode(
            $this->codeGenerator
                ->generate(8)
        );
        $code->setUser($event->getUser());
        $this->em->persist($code);
        $this->em->flush();

        $user = $event->getUser();

        $subject = $this->translate(
            'email.verification.code.subject',
            $user->getLanguage()
        );

        $verifyAccount = $this->translate(
            'email.verification.code.explanation',
            $user->getLanguage()
        );

        $this->bus->dispatch(
            new EmailNotification(
                $user->getEmail(),
                $subject,
                [
                    'subject' => $subject,
                    'verifyAccount' => $verifyAccount,
                    'code' => $code->getCode()
                ],
                'user_account_confirmation'
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
}
