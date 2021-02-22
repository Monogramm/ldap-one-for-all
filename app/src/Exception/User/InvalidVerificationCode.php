<?php


namespace App\Exception\User;

use App\Exception\ApiExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class InvalidVerificationCode extends \RuntimeException implements ApiExceptionInterface
{
    protected $code = 1004;
    protected $httpErrorCode = 400;
    protected $message = 'error.user.invalid.verification.code';

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
