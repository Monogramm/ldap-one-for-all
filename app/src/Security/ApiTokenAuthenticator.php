<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var ApiTokenRepository
     */
    private $tokenRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        ApiTokenRepository $tokenRepository,
        UserRepository $userRepository
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            return false;
        }

        $credentials = [
            'token' => $token,
        ];

        // Retrieve user to switch to from parameters or headers
        $switchUsername = $request->query->get(
            '_switch_user',
            $request->headers->get('http_x_switch_user', $request->headers->get('HTTP_X_SWITCH_USER', null))
        );
        if ($switchUsername !== null) {
            $credentials['switch_user'] = $switchUsername;
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->tokenRepository->findOneBy([
            'token' => $credentials['token']
        ]);

        if (!$token || $token->isNowExpired()) {
            return;
        }

        $tokenUser = $token->getUser();

        // Return switch user if current token is allowed to switch
        // FIXME We don't leverage role hierarchy here, which is a shame...
        if (isset($credentials['switch_user'])
            && '_exit' !== ($switchUsername = $credentials['switch_user'])
            && (in_array('ROLE_SUPER_ADMIN', $tokenUser->getRoles(), true)
                || in_array('ROLE_ALLOWED_TO_SWITCH', $tokenUser->getRoles(), true))) {
            $user = $this->userRepository->findOneBy(
                ['username' => $switchUsername]
            );
            if ($user !== null) {
                $user->addRole('ROLE_PREVIOUS_ADMIN');
                $user->addRole('IS_IMPERSONATOR');
            }

            return $user;
        }

        return $tokenUser;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$user->isEnabled()) {
            return false;
        }

        return true;
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong login password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 401 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request                 $request   The request.
     * @param AuthenticationException $exception An authentication exception.
     *
     * @return void
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request        $request     The request.
     * @param TokenInterface $token       The token generated on success.
     * @param string         $providerKey The provider (i.e. firewall) key.
     *
     * @return void
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
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
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
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
     * @return void
     */
    public function supportsRememberMe()
    {
    }
}
