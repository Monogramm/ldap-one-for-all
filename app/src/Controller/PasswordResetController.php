<?php

namespace App\Controller;

use App\Handler\Security\CreatePasswordResetCodeHandler;
use App\Handler\Security\PasswordResetHandler;
use App\Repository\PasswordResetCodeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{
    /**
     * @Route("/api/password/reset", name="create_password_reset", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function createPasswordResetCode(
        Request $request,
        UserRepository $userRepository,
        CreatePasswordResetCodeHandler $handler
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse([]);
        }

        $handler->handle($user);

        return new JsonResponse([]);
    }

    /**
     * @Route("/api/password/reset/{code}", name="password_reset", methods={"GET"})
     *
     * @return Response
     */
    public function resetPassword(
        string $code,
        PasswordResetCodeRepository $codeRepository,
        PasswordResetHandler $handler
    ): Response {
        $code = $codeRepository->findOneBy([
            'code' => $code
        ]);

        if (!$code) {
            return new Response('', 400);
        }

        $handler->handle($code);

        return $this->redirectToRoute('vue', [
            'vueRouting' => 'login',
            'resetPassword' => true,
        ]);
    }
}
