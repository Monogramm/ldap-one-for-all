<?php


namespace App\Handler\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PasswordChangeHandler
{
    private $emi;

    private $passwordChecker;

    public function __construct(
        EntityManagerInterface $emi,
        PasswordCheckHandler $passwordChecker
    ) {
        $this->emi = $emi;
        $this->passwordChecker = $passwordChecker;
    }

    public function handle(string $newPassword, string $confirmPassword, string $oldPassword, User $user): bool
    {
        // Should throw appropriate exception if not valid
        $isValid = $this->passwordChecker->handle($newPassword, $confirmPassword, $oldPassword, $user);

        if (!$isValid) {
            return false;
        }

        $newPassword = $this->passwordEncoder->encodePassword($user, $newPassword);

        $user->setPassword($newPassword);

        // Revoke all sessions
        foreach ($user->getTokens() as $token) {
            $this->emi->remove($token);
        }
        $this->emi->persist($user);
        $this->emi->flush();

        return true;
    }
}
