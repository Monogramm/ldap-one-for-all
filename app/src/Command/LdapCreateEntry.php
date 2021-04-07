<?php

namespace App\Command;

use App\Service\Ldap\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class LdapCreateEntry extends Command
{
    protected static $defaultName = 'ldap:create:entry';

    /**
     * @var Ldap
     */
    private $ldap;

    /**
     * @var Client
     */
    private $client;

    public function __construct(
        Ldap $ldap,
        Client $client
    ) {
        $this->ldap = $ldap;
        $this->client = $client;
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
            ->setDescription('Creates a ldap Entrie')
            ->setHelp('This command create a entrie in the ldap using a raw query.')
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Query'
            )
            ->addArgument(
                'array',
                InputArgument::REQUIRED,
                'Array'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = $input->getArgument('query');
        $queryArray = $input->getArgument('array');

        $symfonyStyle = new SymfonyStyle($input, $output);

        $resultValid  = $this->isValid($symfonyStyle, $query, $queryArray);

        if ($resultValid) {
            $symfonyStyle->comment("Entrie create :");
            $resultCreate = $this->client->create($query);
            if ($resultCreate) {
                $symfonyStyle->success('Everything is good ! : '.$resultCreate);
                return 0;
            }
            if (!$resultCreate) {
                $symfonyStyle->error("Something went wrong... : ".$resultCreate);
                return 1;
            }
        }
        if (!$resultValid) {
            return 1;
        }
        return 0;
    }

    protected function isValid(SymfonyStyle $ioStyle, $query): bool
    {
        if (empty($query)&& is_string($query)) {
            $ioStyle->error('Username cannot be empty');
            return false;
        }
        return true;
    }
}
