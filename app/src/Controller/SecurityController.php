<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\Security\PasswordChangeHandler;
use App\Handler\Security\PasswordLdapChangeHandler;
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
    private PasswordChangeHandler $localHandler;
    private PasswordLdapChangeHandler $ldapHandler;

    public function __construct(
        PasswordChangeHandler $localHandler,
        PasswordLdapChangeHandler $ldapHandler
    ) {
        $this->localHandler = $localHandler;
        $this->ldapHandler = $ldapHandler;
    }

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
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();
        $oldPassword = $data['oldPassword'] ?? '';
        $handled = $this->changeUserPassword($data['newPassword'], $data['confirmPassword'], $oldPassword, $user);

        return new JsonResponse([], $handled ? Response::HTTP_OK : Response::HTTP_FORBIDDEN);
    }

    /**
     * @Route("/api/admin/user/{user}/password", name="set_password_user", methods={"PUT"})
     *
     * @return JsonResponse
     */
    public function setPassword(
        User $user,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Ignore old password
        $handled = $this->changeUserPassword($data['newPassword'], $data['confirmPassword'], null, $user);

        return new JsonResponse([], $handled ? Response::HTTP_OK : Response::HTTP_FORBIDDEN);
    }

    private function changeUserPassword(string $newPassword, string $confirmPassword, ?string $oldPassword, User $user): bool
    {
        if (null !== $user->getMeta('ldap', null)) {
            $handled = $this->ldapHandler->handle($newPassword, $confirmPassword, $oldPassword, $user);
        } else {
            $handled = $this->localHandler->handle($newPassword, $confirmPassword, $oldPassword, $user);
        }

        return $handled;
    }
}
