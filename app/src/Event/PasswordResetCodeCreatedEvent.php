<?php


namespace App\Event;

use App\Entity\PasswordResetCode;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class PasswordResetCodeCreatedEvent extends Event
{
    public const NAME = 'password.reset.code.created';

    protected $code;

    protected $user;

    public function __construct(User $user, PasswordResetCode $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    public function getPasswordResetCode(): PasswordResetCode
    {
        return $this->code;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
