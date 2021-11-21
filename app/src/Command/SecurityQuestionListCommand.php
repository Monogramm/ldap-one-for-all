<?php

namespace App\Command;

use App\Entity\SecurityQuestion;
use App\Repository\SecurityQuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SecurityQuestionListCommand extends Command
{
    protected static $defaultName = 'app:security-questions:list';

    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var SecurityQuestionRepository
     */
    private $securityQuestionRepository;

    public function __construct(
        EntityManagerInterface $emi,
        SecurityQuestionRepository $securityQuestionRepository
    ) {
        $this->securityQuestionRepository = $securityQuestionRepository;
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
            ->setDescription('List security questions')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'Filter by name'
            )

        ;
    }

    /**
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment("List of security questions:");

        // TODO Filter security questions
        // $name = $input->getOption('name');

        $securityQuestions = $this->securityQuestionRepository->findAll();
        $rows = [];
        foreach ($securityQuestions as $key => $securityQuestion) {
            $rows[$key] = [
                $securityQuestion->getName(),
                json_encode($securityQuestion->getI18n())
            ];
        }

        (new SymfonyStyle($input, $output))
            ->table(['Name', 'i18n'], $rows);

        return 0;
    }
}
