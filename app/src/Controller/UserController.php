<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Exception\User\InvalidVerificationCode;
use App\Handler\UserRegistrationHandler;
use App\Message\EmailNotification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user", name="user-create", methods={"POST"})
     *
     * @return Response
     */
    public function createUserAccount(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserRegistrationHandler $registrationHandler,
        EventDispatcherInterface $dispatcher
    ): Response {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorMessage = (string) $errors;

            return new JsonResponse($errorMessage, 422);
        }

        $registrationHandler->handle($user);

        $dispatcher->dispatch(new UserCreatedEvent($user));

        return new Response('', 201);
    }

    /**
     * @Route("/api/user/verify", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function verifyUser(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        $code = $user->getVerificationCode();

        if (!$code) {
            return new JsonResponse([]);
        }

        if ($data['code'] !== $code->getCode()) {
            throw new InvalidVerificationCode();
        }

        $user->verify();
        $em->persist($user);
        $em->remove($code);
        $em->flush();

        return new JsonResponse([]);
    }

    /**
     * @Route("/api/user/verify/resend", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function resendVerificationCode(
        MessageBusInterface $bus,
        TranslatorInterface $translator,
        string $mailerFrom
    ): JsonResponse {
        $user = $this->getUser();

        $code = $user->getVerificationCode();

        if (!$code) {
            return new JsonResponse([]);
        }

        $subject = $translator->trans(
            'email.verification.code.subject'
        );

        $verifyAccount = $translator->trans(
            'email.verification.code.explanation'
        );

        $bus->dispatch(
            new EmailNotification(
                $user->getEmail(),
                $subject,
                [
                    'subject' => $subject,
                    'verifyAccount' => $verifyAccount,
                    'code' => $code->getCode()
                ],
                'user_account_confirmation'
            ),
            [new AmqpStamp('user-email', AMQP_NOPARAM, [])]
        );

        return new JsonResponse([]);
    }

    /**
     * @Route("/api/user", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getCurrentUser(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return new JsonResponse([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'isVerified' => $user->isVerified(),
            'language' => $user->getLanguage(),
        ]);
    }

    /**
     * @Route("/api/user/disable", methods={"PUT"})
     *
     * @return JsonResponse
     */
    public function disableCurrentUser(EntityManagerInterface $em): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $user->disable();

        $em->persist($user);
        $em->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/api/admin/users", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function findAllByUsername(UserRepository $userRepository, Request $request): JsonResponse
    {
        if (!$request->get('username')) {
            return new JsonResponse();
        }

        $usersArray = $userRepository->findAllLikeUsername(
            $request->get('username')
        );

        return new JsonResponse($usersArray);
    }

    /**
     * @Route("/api/admin/user", name="get_users", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getUsers(
        UserRepository $repository,
        SerializerInterface $serializer,
        Request $request
    ): JsonResponse {
        $page = (int) $request->get('page', 1);
        $itemsPerPage = (int) $request->get('size', 20);

        $filters = json_decode($request->get('filters', '[]'), true);
        $orders  = json_decode($request->get('orders', ''), true);

        if ($page > 0 && $itemsPerPage > 0) {
            $users = $repository->findAllByPage($page, $itemsPerPage, $filters, $orders);
        } else {
            $users = $repository->findAll($filters, $orders);
        }

        $total = count($users);
        $results = $serializer->normalize(
            $users,
            User::class,
            [AbstractNormalizer::GROUPS => 'admin']
        );

        return new JsonResponse([
            'total' => $total,
            'items' => $results
        ]);
    }
}
