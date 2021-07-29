<?php


namespace App\Handler\Security;

use App\Entity\ApiToken;
use App\Entity\User;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var JWTTokenManagerInterface
     */
    protected $jwtManager;
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;
    /**
     * @var EntityManagerInterface
     */
    protected $emi;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $emi
    ) {
        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
        $this->emi = $emi;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->handleAuthenticationSuccess($token->getUser(), $token->getAttributes());
    }

    public function handleAuthenticationSuccess(User $user, array $payload = [], $jwt = null): JWTAuthenticationSuccessResponse
    {
        if (null === $jwt) {
            $jwt = $this->jwtManager->createFromPayload($user, $payload);
        }

        $response = new JWTAuthenticationSuccessResponse($jwt);
        $event    = new AuthenticationSuccessEvent(['token' => $jwt], $user, $response);

        $token = new ApiToken();
        $token->setToken($jwt);
        $expiration = Carbon::now()->addDays(7);
        $token->setExpiredAt($expiration);
        $user->addToken($token);
        $this->emi->persist($user);
        $this->emi->persist($token);
        $this->emi->flush();

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $response->setData($event->getData());

        return $response;
    }
}
