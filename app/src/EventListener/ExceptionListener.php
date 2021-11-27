<?php


namespace App\EventListener;

use App\Exception\ApiExceptionInterface;
use App\Exception\EntityValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionListener
{
    private $logger;

    private $translator;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * Catches kernel exception to create error response.
     *
     * @param ExceptionEvent $event exception to handle
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Always log exception errors
        $this->logger->error($exception);

        // TODO Do not check env var but get '%kernel.environment%'
        $appEnv = $_ENV['APP_ENV'];

        if ($appEnv !== 'dev'
            && !($exception instanceof HttpExceptionInterface)
        ) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof ApiExceptionInterface) {
            $message = [
                'code' => $exception->getCode(),
                'message' => $this
                    ->translator
                    ->trans(
                        $exception->getMessage()
                    ),
            ];

            if ($exception instanceof EntityValidationException) {
                $message['errors'] = $exception->getErrors();
            }

            $response = new JsonResponse(
                $message,
                $exception->getStatusCode()
            );
            $response->headers->replace($exception->getHeaders());

            $event->setResponse($response);
            return;
        }

        $message = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];

        // Customize your response object to display the exception details
        $response = new JsonResponse($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
