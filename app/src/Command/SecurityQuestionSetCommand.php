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

class SecurityQuestionSetCommand extends Command
{
    protected const SEPARATOR = '=';

    protected static $defaultName = 'app:security-questions:set';

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
            ->setDescription('Sets a security question')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name'
            )
            ->addOption(
                'i18n',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Security question internationalization. Expected in format "locale'
                . self::SEPARATOR . 'question"'
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

        // Checking input format
        if ($this->isInvalid($ioStyle, $name)) {
            return 1;
        }

        // Checking conflicts
        $securityQuestion = $this->findByName($name);
        if (empty($securityQuestion)) {
            $securityQuestion = new SecurityQuestion($name);
        }

        // Creating Security Question
        $i18n = $input->getOption('i18n');
        foreach ($i18n as $translation) {
            $sepPos = strpos($translation, self::SEPARATOR);
            if (false === $sepPos) {
                $ioStyle->warning("Missing separator");
                continue;
            }

            $locale = trim(substr($translation, 0, $sepPos));
            if (empty($locale)) {
                $ioStyle->warning("Missing locale");
                continue;
            }

            $question = trim(substr($translation, $sepPos + 1));
            if (empty($question)) {
                $ioStyle->warning("Missing translation for locale $locale");
                continue;
            }

            $securityQuestion
                ->setI18nQuestion($locale, $question);
        }

        $this->emi->persist($securityQuestion);
        $this->emi->flush();

        $ioStyle->success("Security Question '$name' set");

        return 0;
    }

    protected function isInvalid(SymfonyStyle $ioStyle, String $name): bool
    {
        $invalid = false;

        if (empty($name)) {
            $ioStyle->error('Name cannot be empty');
            $invalid = true;
        }

        return $invalid;
    }

    protected function findByName(String $name): ?SecurityQuestion
    {
        return $this->securityQuestionRepository->findOneBy(['name' => $name]);
    }
}
