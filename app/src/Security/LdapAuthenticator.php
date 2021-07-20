<?php


namespace App\Security;

use App\Entity\Parameter;
use App\Entity\User;
use App\Handler\Security\AuthenticationSuccessHandler;
use App\Repository\ParameterRepository;
use App\Repository\UserRepository;
use App\Service\Ldap\Client;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LdapAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var AuthenticationSuccessHandler
     */
    private $successHandler;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Client
     */
    private $ldap;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var array
     */
    private $ldapConfig;
    /**
     * @var ParameterRepository
     */
    private $parameterRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Client $ldap,
        UserRepository $userRepository,
        AuthenticationSuccessHandler $successHandler,
        EntityManagerInterface $em,
        RouterInterface $router,
        ParameterRepository $parameterRepository,
        array $ldapConfig,
        LoggerInterface $logger
    ) {
        $this->ldap = $ldap;
        $this->successHandler = $successHandler;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->router = $router;
        $this->parameterRepository = $parameterRepository;
        $this->ldapConfig = $ldapConfig;
        $this->logger = $logger;
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

    public function supports(Request $request)
    {
        return 'api_ldap_auth' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$this->ldapConfig['enabled']) {
            return null;
        }
        if (!isset($credentials['username']) && !isset($credentials['password'])) {
            return null;
        }

        $username = $credentials['username'];
        $password = $credentials['password'];
        try {
            $entry = $this->ldap->check($username, $password);
        } catch (\Throwable $e) {
            $this->logger->debug('Failed to check against LDAP. Error message: %s'.$e->getMessage());
            return null;
        }

        if (!isset(
            $entry->getAttribute($this->ldapConfig['mail_key'])[0],
            $entry->getAttribute($this->ldapConfig['uid_key'])[0]
        )) {
            $this->logger->debug('Failed to authenticate user against LDAP.');
            return null;
        }

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $user = (new User())
                ->setEmail($entry->getAttribute($this->ldapConfig['mail_key'])[0])
                ->setUsername($entry->getAttribute($this->ldapConfig['uid_key'])[0])
                ->setPassword(LoginFormAuthenticator::NO_PASSWORD);
        }

        // Always update user roles based on LDAP configuration
        $userRoles = $this->getUserRoles($entry->getDn());
        $user->setRoles($userRoles);
        // Always verify LDAP user
        $user->verify();

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->successHandler->onAuthenticationSuccess($request, $token);
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
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    private function getUserDefaultRole(): ?string
    {
        /**
         * @var Parameter|null $parameter
         */
        $parameter = $this->parameterRepository->findByName('LDAP_USER_DEFAULT_ROLE');

        if (!$parameter) {
            return null;
        }

        return $parameter->getValue();
    }

    /**
     * @return string[]
     *
     * @psalm-return list<string>
     */
    private function getUserRoles(string $fullDn): array
    {
        $userRoles = [];

        $defaultRole = $this->getUserDefaultRole();
        if (!empty($defaultRole)) {
            $userRoles[] = $defaultRole;
        }

        $groupBase = $this->ldapConfig['group_base_dn'];
        if (!empty($groupBase)) {
            $userGroup = $this->getLdapGroupForUserRole();
            $adminGroup = $this->getLdapGroupForAdminRole();

            // TODO Search user in User or Admin groups only
            $groupQuery = sprintf(
                '(&(%s=%s)%s)',
                $this->ldapConfig['group_key'],
                $fullDn,
                $this->ldapConfig['group_query']
            );

            $ldapGroups = $this->ldap->search($groupQuery, $groupBase);
            $defaultRole = '';
            if (0 !== count($ldapGroups)) {
                foreach ($ldapGroups as $ldapGroup) {
                    if ($adminGroup === $ldapGroup->getDn()) {
                        $userRoles[] = 'ROLE_ADMIN';
                    } elseif ($userGroup === $ldapGroup->getDn()) {
                        $userRoles[] = 'ROLE_USER';
                    }
                }
            }
        }

        return $userRoles;
    }

    private function getLdapGroupForAdminRole(): ?string
    {
        /**
         * @var Parameter|null $parameter
         */
        $parameter = $this->parameterRepository->findByName('LDAP_GROUP_ADMIN');

        if (!$parameter) {
            return null;
        }

        return $parameter->getValue();
    }

    private function getLdapGroupForUserRole(): ?string
    {
        /**
         * @var Parameter|null $parameter
         */
        $parameter = $this->parameterRepository->findByName('LDAP_GROUP_USER');

        if (!$parameter) {
            return null;
        }

        return $parameter->getValue();
    }
}
