<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSetPasswordCommand extends Command
{
    protected static $defaultName = 'app:users:set-password';

    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var PasswordGenerator
     */
    private $passwordGenerator;

    public function __construct(
        EntityManagerInterface $emi,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        PasswordGenerator $passwordGenerator
    ) {
        $this->emi = $emi;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->passwordGenerator = $passwordGenerator;

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
            ->setDescription('Set user password')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_REQUIRED,
                'User password (randomly generated if not defined)'
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
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getOption('password');

        // Checking input format
        $invalid = false;
        if (empty($username)) {
            $io->error('Username cannot be empty');
            $invalid = true;
        }
        if (empty($password)) {
            $password = $this->passwordGenerator->generate(12);
            $io->warning("No password provided. Randomly generating a new password: $password");
        }
        // TODO Check password security?

        if ($invalid) {
            return 1;
        }

        // Setting user password
        $user = $this->findByUsername($username);
        if (empty($user)) {
            $io->error('No user found with this username');
            return 1;
        }

        $user->setPassword(
            $this->passwordEncoder
                    ->encodePassword($user, $password)
        );

        $this->emi->persist($user);
        $this->emi->flush();

        $io->success("User '$username' password reset");

        return 0;
    }

    protected function findByUsername(String $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }
}
