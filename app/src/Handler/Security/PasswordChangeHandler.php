<?php


namespace App\Handler\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordChangeHandler
{
    private $emi;

    private $passwordChecker;

    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $emi,
        PasswordCheckHandler $passwordChecker,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->emi = $emi;
        $this->passwordChecker = $passwordChecker;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(string $newPassword, string $confirmPassword, ?string $oldPassword, User $user): bool
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
