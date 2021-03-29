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

class LdapDeleteEntry extends Command
{
    protected static $defaultName = 'ldap:delete:entry';

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
        ->setDescription('Delete a ldap Entrie')
        ->setHelp('This command delete a entrie in the ldap using a raw query.')
        ->addArgument(
            'query',
            InputArgument::REQUIRED,
            'Query'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = $input->getArgument('query');

        return 0;
    }
}