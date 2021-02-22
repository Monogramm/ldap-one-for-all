<?php


namespace App\Exception\User;

use App\Exception\ApiExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class UsernameAlreadyTaken extends \RuntimeException implements ApiExceptionInterface
{
    protected $code = 1002;
    protected $httpErrorCode = 409;
    protected $message = 'error.user.username.already.taken';

    public function __construct(Throwable $previous = null)
    {
        parent::__construct($this->message, $this->code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHttpErrorCode(): int
    {
        return $this->httpErrorCode;
    }
}
