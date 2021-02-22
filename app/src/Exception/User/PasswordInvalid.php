<?php


namespace App\Exception\User;

use App\Exception\ApiExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class PasswordInvalid extends \RuntimeException implements ApiExceptionInterface
{
    protected $code = 1003;
    protected $httpErrorCode = 403;
    protected $message = 'error.user.password.invalid';

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
