<?php


namespace App\Exception\User;

use App\Exception\ApiExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class EmailAlreadyTaken extends \RuntimeException implements ApiExceptionInterface
{
    protected $code = 1001;
    protected $httpErrorCode = 409;
    protected $message = 'error.user.email.already.taken';

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
