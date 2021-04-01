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

class LdapUpdateEntry extends Command
{
    protected static $defaultName = 'ldap:update:entry';

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
            ->setDescription('Update a ldap Entrie')
            ->setHelp('This command create a entrie in the ldap using a raw query.')
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Query'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = $input->getArgument('query');
        $symfonyStyle = new SymfonyStyle($input, $output);

        $resultValid = $this->isValid($symfonyStyle, $query);

        if ($resultValid) {
            $symfonyStyle->comment("update entry :");

            // Get the ldap informations from the ldap
            $resultUpdate = $this->client->update($query);
            if ($resultUpdate) {
                $symfonyStyle->success('Everything is good ! : '.$resultUpdate);
                return 0;
            }
            if (!$resultUpdate) {
                $symfonyStyle->error("Something went wrong... : ".$resultUpdate);
                return 1;
            }
        }
        if (!$resultValid) {
            return 0;
        }
    }
    
    protected function isValid(SymfonyStyle $ioStyle, $query): bool
    {
        if (empty($query)&& is_string($query)) {
            $ioStyle->error('Query cannot be empty');
            return false;
        }
        return true;
    }

}