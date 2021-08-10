<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:users:create';

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

    public function __construct(
        EntityManagerInterface $emi,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) {
        $this->emi = $emi;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct(self::$defaultName);
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Creates a user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'User email'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'User password'
            )
            ->addOption(
                'role',
                null,
                InputOption::VALUE_REQUIRED,
                'User role. Can be USER or ADMIN',
                'USER'
            )
            ->addOption(
                'verified',
                null,
                InputOption::VALUE_NONE,
                'Verify user account'
            )

        ;
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ioStyle = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');

        // Checking input format
        if ($this->isInvalid($ioStyle, $username, $email, $password)) {
            return 1;
        }

        // Checking conflicts
        if ($this->isInConflict($ioStyle, $username, $email)) {
            return 0;
        }

        // Creating user
        $role = strtoupper($input->getOption('role'));
        $isVerified = $input->getOption('verified');

        $user = new User();
        $user->setUsername($username)
            ->setPassword(
                $this->passwordEncoder
                    ->encodePassword($user, $password)
            )
            ->setEmail($email)
            ->setRoles(['ROLE_' . $role]);

        if ($isVerified) {
            $user->verify();
        }

        $this->emi->persist($user);
        $this->emi->flush();

        $ioStyle->success("User '$username' created");

        return 0;
    }

    protected function isInvalid(SymfonyStyle $ioStyle, String $username, String $email, String $password): bool
    {
        $invalid = false;

        if (empty($username)) {
            $ioStyle->error('Username cannot be empty');
            $invalid = true;
        }
        if (empty($email)) {
            $ioStyle->error('Email cannot be empty');
            $invalid = true;
        }
        if (empty($password)) {
            $ioStyle->error('Password cannot be empty');
            $invalid = true;
            // TODO Generate random password if empty?
        }

        // TODO Check password security?

        return $invalid;
    }

    protected function isInConflict(SymfonyStyle $ioStyle, String $username, String $email): bool
    {
        $conflict = false;

        if ($this->findByUsername($username)) {
            $ioStyle->warning('This username is already taken');
            $conflict = true;
        }
        if ($this->findByEmail($email)) {
            $ioStyle->warning('This email address is already taken');
            $conflict = true;
        }

        return $conflict;
    }

    protected function findByUsername(String $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    protected function findByEmail(String $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
