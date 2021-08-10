<?php


namespace App\Handler\Security;

use App\Entity\PasswordResetCode;
use App\Entity\User;
use App\Event\PasswordResetCodeCreatedEvent;
use App\Service\JWTEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreatePasswordResetCodeHandler
{
    private $encoder;

    private $emi;

    private $eventDispatcher;

    public function __construct(
        JWTEncoder $encoder,
        EntityManagerInterface $emi,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->encoder = $encoder;
        $this->emi = $emi;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(User $user): void
    {
        $data = [
            'userId' => (string) $user->getId(),
        ];

        $jwtCode = $this->encoder->encode($data);

        $code = new PasswordResetCode();
        $code->setCode($jwtCode);

        $this->emi->persist($code);
        $this->emi->flush();

        $this->eventDispatcher->dispatch(
            new PasswordResetCodeCreatedEvent($user, $code)
        );
    }
}
