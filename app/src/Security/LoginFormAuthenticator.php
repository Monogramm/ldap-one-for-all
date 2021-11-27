<?php

namespace App\Security;

use App\Handler\Security\AuthenticationSuccessHandler;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    public const NO_PASSWORD = '!$';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var AuthenticationSuccessHandler
     */
    private $successHandler;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        AuthenticationSuccessHandler $successHandler
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->successHandler = $successHandler;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request)
    {
        return 'api_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * @return \App\Entity\User|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!isset($credentials['username'])) {
            return null;
        }
        $usernameOrEmail = $credentials['username'];

        // Load user by username or email
        return $this->userRepository->loadUserByUsername($usernameOrEmail);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$user->isEnabled()) {
            return false;
        }

        if (!isset($credentials['password'])) {
            return false;
        }

        if ($credentials['password'] === self::NO_PASSWORD) {
            return false;
        }

        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return \Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $token->setAttribute('source', 'local');
        $token->setAttribute('provider', $providerKey);
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *
     * - For a form login, you might redirect to the login page
     *
     *     return new RedirectResponse('/login');
     *
     * - For an API token authentication system, you return a 401 response
     *
     *     return new Response('Auth header required', 401);
     *
     * @param Request                 $request       The request.
     * @param AuthenticationException $authException An authentication exception.
     *
     * @return void
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    /**
     * Does this method support remember me cookies?
     *
     * Remember me cookie will be set if *all* of the following are met:
     *  A) This method returns true
     *  B) The remember_me key under your firewall is configured
     *  C) The "remember me" functionality is activated. This is usually
     *      done by having a _remember_me checkbox in your form, but
     *      can be configured by the "always_remember_me" and "remember_me_parameter"
     *      parameters under the "remember_me" firewall key
     *  D) The onAuthenticationSuccess method returns a Response object
     *
     * @return false
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
