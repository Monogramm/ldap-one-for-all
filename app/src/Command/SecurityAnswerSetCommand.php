<?php

namespace App\Command;

use App\Service\SecurityAnswerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class SecurityAnswerSetCommand extends Command
{
    protected static $defaultName = 'app:security-answers:set';

    /**
     * @var SecurityAnswerManager
     */
    private $securityAnswerManager;

    public function __construct(
        SecurityAnswerManager $securityAnswerManager
    ) {
        $this->securityAnswerManager = $securityAnswerManager;

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
            ->setDescription('Sets a security answer for a user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addArgument(
                'question',
                InputArgument::REQUIRED,
                'Security question name'
            )
            ->addOption(
                'answer',
                '',
                InputOption::VALUE_REQUIRED,
                'Security answer. User will be prompted answer if not specified'
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
        $question = $input->getArgument('question');
        $answer = strtolower(trim($input->getOption('answer')));

        // Checking input format
        if ($this->isInvalid($ioStyle, $username, $question, $answer)) {
            return 1;
        }

        // Setting Security Answer
        $this->securityAnswerManager->getAndSetUserSecurityAnswer($username, $question, $answer);

        $ioStyle->success("Security Answer '$question' set for user '$username'");

        return 0;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');

        $answer = $input->getOption('answer');
        if (empty($answer)) {
            $question = new Question('No answer provided. Please enter answer for secret question: ', false);
            $question->setHidden(true);

            $newAnswer = $questionHelper->ask($input, $output, $question);
            if (false === $newAnswer) {
                return 0;
            }
            $input->setOption('answer', $newAnswer);
        }
    }

    protected function isInvalid(SymfonyStyle $ioStyle, String $username, String $name, String $answer): bool
    {
        $invalid = false;

        if (empty($username)) {
            $ioStyle->error('Username cannot be empty');
            $invalid = true;
        }

        if (empty($name)) {
            $ioStyle->error('Name cannot be empty');
            $invalid = true;
        }

        if (empty($answer)) {
            $ioStyle->error('Answer cannot be empty');
            $invalid = true;
        }

        return $invalid;
    }
}
