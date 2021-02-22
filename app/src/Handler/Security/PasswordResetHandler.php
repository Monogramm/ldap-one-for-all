<?php


namespace App\Handler\Security;

use App\Entity\PasswordResetCode;
use App\Entity\User;
use App\Message\EmailNotification;
use App\Repository\UserRepository;
use App\Service\JWTEncoder;
use App\Service\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordResetHandler
{
    private $jwtEncoder;

    private $userRepository;

    private $passwordEncoder;

    private $em;

    private $passwordGenerator;

    private $bus;

    private $translator;

    private $mailerFrom;

    public function __construct(
        JWTEncoder $jwtEncoder,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $em,
        PasswordGenerator $passwordGenerator,
        MessageBusInterface $bus,
        TranslatorInterface $translator,
        string $mailerFrom
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->passwordGenerator = $passwordGenerator;
        $this->bus = $bus;
        $this->translator = $translator;
        $this->mailerFrom = $mailerFrom;
    }

    public function handle(PasswordResetCode $passwordReset): void
    {
        $data = $this->jwtEncoder->decode(
            $passwordReset->getCode()
        );

        $user = $this->userRepository->find($data['userId']);

        if (!$user) {
            return;
        }

        $newPassword = $this->passwordGenerator->generate(10);

        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword($user, $newPassword)
        );

        $this->em->persist($user);
        $this->em->remove($passwordReset);
        $this->em->flush();

        $this->sendEmailWithNewPasswordToUser($user, $newPassword);
    }

    private function sendEmailWithNewPasswordToUser(User $user, string $newPassword): void
    {
        $subject = $this->translate(
            'email.password.new.subject',
            $user->getLanguage()
        );

        $explanation = $this->translate(
            'email.password.new.explanation',
            $user->getLanguage()
        );

        $this->bus->dispatch(
            new EmailNotification(
                $user->getEmail(),
                $subject,
                [
                    'subject' => $subject,
                    'explanation' => $explanation,
                    'newPassword' => $newPassword
                ],
                'password_new'
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
