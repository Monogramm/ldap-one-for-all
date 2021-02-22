<?php


namespace App\Handler\Security;

use App\Entity\User;
use App\Exception\User\PasswordInvalid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordChangeHandler
{
    private $em;

    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(string $newPassword, string $confirmPassword, string $oldPassword, User $user): void
    {
        $isValid = $this->passwordEncoder->isPasswordValid($user, $oldPassword);

        if (!$isValid) {
            throw new PasswordInvalid();
        }

        if ($newPassword !== $confirmPassword) {
            throw new PasswordInvalid();
        }

        $newPassword = $this->passwordEncoder->encodePassword($user, $newPassword);

        $user->setPassword($newPassword);

        foreach ($user->getTokens() as $token) {
            $this->em->remove($token);
        }
        $this->em->persist($user);
        $this->em->flush();
    }
}
