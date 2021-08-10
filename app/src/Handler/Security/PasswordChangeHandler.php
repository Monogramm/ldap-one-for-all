<?php


namespace App\Handler\Security;

use App\Entity\User;
use App\Exception\User\PasswordInvalid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordChangeHandler
{
    private $emi;

    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $emi,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->emi = $emi;
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
            $this->emi->remove($token);
        }
        $this->emi->persist($user);
        $this->emi->flush();
    }
}
