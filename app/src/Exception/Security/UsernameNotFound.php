<?php


namespace App\Exception\Security;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UsernameNotFound extends HttpException implements ApiExceptionInterface
{
    protected const ERROR_CODE  = 1001;
    protected const STATUS_CODE = Response::HTTP_NOT_FOUND;
    protected const MESSAGE     = 'error.security.username.not.found';

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
