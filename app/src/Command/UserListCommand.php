<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserListCommand extends Command
{
    protected static $defaultName = 'app:users:list';

    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $emi,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->emi = $emi;

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
            ->setDescription('List users')
            ->addOption(
                'username',
                null,
                InputOption::VALUE_REQUIRED,
                'Filter by username'
            )
            ->addOption(
                'email',
                null,
                InputOption::VALUE_REQUIRED,
                'Filter by email'
            )
            ->addOption(
                'role',
                null,
                InputOption::VALUE_REQUIRED,
                'Filter by user role. Can be USER or ADMIN'
            )
            ->addOption(
                'verified',
                null,
                InputOption::VALUE_NONE,
                'Filter by verified status'
            )

        ;
    }

    /**
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment("List of users:");

        // TODO Filter users
        // $username = $input->getOption('username');
        // $email = $input->getOption('email');
        // $role = $input->getOption('role');
        // $isVerified = $input->getOption('verified');

        $users = $this->userRepository->findAll();
        $rows = [];
        foreach ($users as $key => $user) {
            $rows[$key] = [$user->getUsername(),
                $user->getEmail(),
                $user->getLanguage(),
                $user->isVerified(),
                $user->isEnabled()];
        }

        (new SymfonyStyle($input, $output))
            ->table(['Username', 'Email', 'Language', 'Verified', 'Enabled'], $rows);

        return 0;
    }
}
