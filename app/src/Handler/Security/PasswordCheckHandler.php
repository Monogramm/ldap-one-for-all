<?php


namespace App\Handler\Security;

use App\Entity\User;
use App\Exception\User\ConfirmPasswordInvalid;
use App\Exception\User\CurrentPasswordInvalid;
use App\Exception\User\NewPasswordInvalid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordCheckHandler
{
    private $passwordEncoder;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(string $newPassword, string $confirmPassword, ?string $oldPassword, User $user): bool
    {
        if (null !== $oldPassword) {
            $isValid = $this->passwordEncoder->isPasswordValid($user, $oldPassword);

            if (!$isValid) {
                throw new CurrentPasswordInvalid();
            }
        }

        if ($newPassword === $oldPassword) {
            throw new NewPasswordInvalid(NewPasswordInvalid::MSG_SAME_CURRENT);
        }

        if ($newPassword  === $user->getEmail()) {
            throw new NewPasswordInvalid(NewPasswordInvalid::MSG_SAME_EMAIL);
        }

        if ($newPassword  === $user->getUsername()) {
            throw new NewPasswordInvalid(NewPasswordInvalid::MSG_SAME_USERNAME);
        }

        // TODO Check size and complexity

        if ($newPassword !== $confirmPassword) {
            throw new ConfirmPasswordInvalid();
        }

        return true;
    }
}
