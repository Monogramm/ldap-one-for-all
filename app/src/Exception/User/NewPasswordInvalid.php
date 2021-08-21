<?php


namespace App\Exception\User;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class NewPasswordInvalid extends HttpException implements ApiExceptionInterface
{
    protected const ERROR_CODE  = 1004;
    protected const STATUS_CODE = Response::HTTP_FORBIDDEN;

    public const MSG_SAME_CURRENT  = 'error.user.password.not-current';
    public const MSG_SAME_USERNAME = 'error.user.password.not-username';
    public const MSG_SAME_EMAIL    = 'error.user.password.not-email';
    public const MSG_TOO_SHORT     = 'error.user.password.too-short';

    public function __construct(string $message, Throwable $previous = null, array $headers = [])
    {
        parent::__construct(
            self::STATUS_CODE,
            $message,
            $previous,
            $headers,
            self::ERROR_CODE
        );
    }
}
