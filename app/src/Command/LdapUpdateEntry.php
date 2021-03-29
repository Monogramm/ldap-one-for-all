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
        $io = new SymfonyStyle($input, $output);

        if($this->isValid($io, $query))
        {
            $io->comment("update entry :");

            // Get the ldap informations from the ldap
            $result = $this->client->update($query);
            if($result)
            {
                $io->success('Everything is good ! : '.$result);
                return 0;
            }
            else
            {
                $io->error("Something went wrong... : ".$result);
                return 1;
            }  
        }
        else
            return 0;
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