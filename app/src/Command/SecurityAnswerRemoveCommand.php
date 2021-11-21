<?php

namespace App\Command;

use App\Entity\SecurityAnswer;
use App\Repository\SecurityAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SecurityAnswerRemoveCommand extends Command
{
    protected static $defaultName = 'app:security-answers:remove';

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
            ->setDescription('Removes security answers of a user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addOption(
                'question',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Security question name(s) to remove. Will remove all answers if none specified'
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

        // Checking entities exists
        $securityAnswers = $this->findAllByUsername($username);
        if (empty($securityAnswers)) {
            return 0;
        }

        // Removing Security Answer(s)
        $questions = $input->getOption('question');
        if (empty($questions)) {
            foreach ($securityAnswers as $securityAnswer) {
                $this->emi->remove($securityAnswer);
            }
            $this->emi->flush();
            $ioStyle->success("Security Answers of user '$username' removed");
        } else {
            foreach ($securityAnswers as $securityAnswer) {
                $question = $securityAnswer->getName();
                if (in_array($question, $questions)) {
                    $this->emi->remove($securityAnswer);
                    $this->emi->flush();

                    $ioStyle->success("Security Answer '$question' of user '$username' removed");
                }
            }
        }

        return 0;
    }

    protected function findAllByUsername(string $username)
    {
        return $this->securityAnswerRepository->findAllByUsernameOrEmail($username, $username);
    }
}
