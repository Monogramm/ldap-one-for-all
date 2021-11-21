<?php

namespace App\Command;

use App\Entity\SecurityQuestion;
use App\Repository\SecurityQuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SecurityQuestionRemoveCommand extends Command
{
    protected static $defaultName = 'app:security-questions:remove';

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
        $this->emi = $emi;
        $this->securityQuestionRepository = $securityQuestionRepository;

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
            ->setDescription('Removes a security question or its translations')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name'
            )
            ->addOption(
                'i18n',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Remove only security question specified locales'
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
        $name = $input->getArgument('name');

        // Checking entity exists
        $securityQuestion = $this->findByName($name);
        if (empty($securityQuestion)) {
            return 0;
        }

        // Removing Security Question
        $i18n = $input->getOption('i18n');
        if (empty($i18n)) {
            $this->emi->remove($securityQuestion);
            $this->emi->flush();

            $ioStyle->success("Security Question '$name' removed");
        } else {
            foreach ($i18n as $locale) {
                $securityQuestion
                    ->unsetI18nQuestion($locale);
            }

            $this->emi->persist($securityQuestion);
            $this->emi->flush();

            $ioStyle->success("Security Question '$name' translation(s) removed");
        }

        return 0;
    }

    protected function findByName(String $name): ?SecurityQuestion
    {
        return $this->securityQuestionRepository->findOneBy(['name' => $name]);
    }
}
