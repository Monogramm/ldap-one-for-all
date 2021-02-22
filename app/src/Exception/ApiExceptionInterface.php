<?php


namespace App\Exception;

interface ApiExceptionInterface
{
    public function getHttpErrorCode(): int;

    public function getStatusCode(): int;
}
