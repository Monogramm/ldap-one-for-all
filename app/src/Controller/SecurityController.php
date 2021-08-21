<?php

namespace App\Controller;

use App\Handler\Security\PasswordChangeHandler;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login")
     *
     * @return void
     */
    public function login(): void
    {
    }

    /**
     * @Route("/api/login/ldap", name="api_ldap_auth")
     *
     * @return void
     */
    public function loginLdap(): void
    {
    }

    /**
     * @Route("/api/logout", name="api_logout", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function logout(
        Request $request,
        ApiTokenRepository $tokenRepository,
        EntityManagerInterface $emi
    ): JsonResponse {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        $token = $tokenRepository->findOneBy(['token' => $token]);

        if (!$token) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $emi->remove($token);
        $emi->flush();

        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @Route("/api/user/password", name="password_change", methods={"PUT"})
     *
     * @return JsonResponse
     */
    public function changePassword(
        PasswordChangeHandler $handler,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();

        $handler->handle($data['newPassword'], $data['confirmPassword'], $data['oldPassword'], $user);

        return new JsonResponse([], Response::HTTP_OK);
    }
}
