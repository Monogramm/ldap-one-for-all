<?php

namespace App\Command;

use App\Entity\SecurityAnswer;
use App\Repository\SecurityAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SecurityAnswerListCommand extends Command
{
    protected static $defaultName = 'app:security-answers:list';

    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var SecurityAnswerRepository
     */
    private $securityAnswerRepository;

    public function __construct(
        EntityManagerInterface $emi,
        SecurityAnswerRepository $securityAnswerRepository
    ) {
        $this->emi = $emi;
        $this->securityAnswerRepository = $securityAnswerRepository;

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
            ->setDescription('List security answers of a user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username to retrieve security answers'
            )

        ;
    }

    /**
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $io->comment("List of security questions with answer from user '$username':");

        $securityAnswers = $this->findAllByUsername($username);
        $rows = [];
        foreach ($securityAnswers as $key => $securityAnswer) {
            $rows[$key] = [
                $securityAnswer->getQuestion()->getName()
            ];
        }

        (new SymfonyStyle($input, $output))
            ->table(['Security Question'], $rows);

        return 0;
    }

    protected function findAllByUsername(string $username)
    {
        return $this->securityAnswerRepository->findAllByUsernameOrEmail($username, $username);
    }
}
