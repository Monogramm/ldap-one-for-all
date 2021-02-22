<?php


namespace App\Handler;

use App\Entity\User;
use App\Exception\User\EmailAlreadyTaken;
use App\Exception\User\UsernameAlreadyTaken;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegistrationHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function handle(User $user): void
    {
        $users = $this->userRepository->findUsersByEmail($user->getEmail());

        if (count($users) > 0) {
            throw new EmailAlreadyTaken();
        }

        $users = $this->userRepository->findUsersByUserName($user->getUsername());

        if (count($users) > 0) {
            throw new UsernameAlreadyTaken();
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        $this->em->persist($user);
        $this->em->flush();
    }
}
