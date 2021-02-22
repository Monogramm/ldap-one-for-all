<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\EmailNotification;
use App\Repository\ParameterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    /**
     * @Route("/api/support/email/send", name="support_send_email", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function sendEmail(
        Request $request,
        MessageBusInterface $bus,
        ParameterRepository $parameterRepository
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $parameter = $parameterRepository
            ->findByName('APP_SUPPORT_EMAIL');

        if (!$parameter) {
            throw new \RuntimeException("Parameter \"APP_SUPPORT_EMAIL\" not found");
        }

        $bus->dispatch(
            new EmailNotification(
                $user->getEmail(),
                $data['subject'],
                [
                    'subject' => $data['subject'],
                    'message' => $data['message']
                ],
                'to_support',
                $parameter->getValue()
            )
        );

        return new JsonResponse([]);
    }
}
