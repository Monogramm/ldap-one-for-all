<?php


namespace App\Handler;

use App\Entity\User;
use App\Exception\User\EmailAlreadyTaken;
use App\Exception\User\RegistrationDisabled;
use App\Exception\User\UsernameAlreadyTaken;
use App\Repository\ParameterRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegistrationHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ParameterRepository
     */
    private $parameterRepository;

    public function __construct(
        EntityManagerInterface $emi,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        ParameterRepository $parameterRepository
    ) {
        $this->emi = $emi;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->parameterRepository = $parameterRepository;
    }

    public function handle(User $user): User
    {
        if (!$this->isRegistrationEnabled()) {
            throw new RegistrationDisabled();
        }

        $users = $this->userRepository->findAllByEmail($user->getEmail());

        if (count($users) > 0) {
            throw new EmailAlreadyTaken();
        }

        $users = $this->userRepository->findAllByUsername($user->getUsername());

        if (count($users) > 0) {
            throw new UsernameAlreadyTaken();
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        // Always force registered user status and role
        $user->enable();
        $user->setRoles(['ROLE_USER']);
        $user->unverify();

        $this->emi->persist($user);
        $this->emi->flush();

        return $user;
    }

    private function getRegistrationEnabled(): ?string
    {
        /**
         * @var Parameter|null $parameter
         */
        $parameter = $this->parameterRepository->findByName('APP_REGISTRATION_ENABLED');

        if (!$parameter) {
            return null;
        }

        return $parameter->getValue();
    }

    /**
     * Is user registration currently enabled for this app.
     *
     * @return bool
     */
    public function isRegistrationEnabled(): bool {
        $value = $this->getRegistrationEnabled();

        return $value === '1';
    }
}
