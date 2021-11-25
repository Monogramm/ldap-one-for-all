<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Entity validation exception.
 */
class EntityValidationException extends HttpException implements ApiExceptionInterface
{
    protected const ERROR_CODE  = 0;
    protected const STATUS_CODE = Response::HTTP_FORBIDDEN;
    protected const MESSAGE     = 'error.entity.validation';

    private $errors;

    public function __construct(ConstraintViolationListInterface $errors, Throwable $previous = null, array $headers = [])
    {
        $errorsArray = [];

        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $property = $error->getPropertyPath();

            if (!isset($errorsArray[$property])) {
                $errorsArray[$property] = [];
            }

            $errorsArray[$property][] = $error->getMessage();
        }
        $this->errors = $errorsArray;

        // XXX Save errors message?
        //$errorsMessage = (string) $errors;

        parent::__construct(
            self::STATUS_CODE,
            self::MESSAGE,
            $previous,
            $headers,
            self::ERROR_CODE
        );
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
