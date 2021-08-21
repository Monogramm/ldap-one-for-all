<?php


namespace App\Exception\User;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ConfirmPasswordInvalid extends HttpException implements ApiExceptionInterface
{
    protected const ERROR_CODE  = 1005;
    protected const STATUS_CODE = Response::HTTP_FORBIDDEN;
    protected const MESSAGE     = 'error.user.password.confirm';

    public function __construct(Throwable $previous = null, array $headers = [])
    {
        parent::__construct(
            self::STATUS_CODE,
            self::MESSAGE,
            $previous,
            $headers,
            self::ERROR_CODE
        );
    }
}
