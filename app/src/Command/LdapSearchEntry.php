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

class LdapSearchEntry extends Command
{
    protected static $defaultName = 'ldap:search:entry';

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
            ->setDescription('Search a ldap Entrie')
            ->setHelp('This command give you the result to a raw query adress to the ldap.')
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

        $resultValid  = $this->isValid($symfonyStyle, $query);

        if ($resultValid) {
            $symfonyStyle->comment("List of entry:");

            // Get the ldap informations from the ldap
            $data = $this->client->search($query);
            
            //Creating an associative array for fetching the data with 'key' and 'value'
            //Create two array with all the datas for the SymfonyStyle->table function
            for ($i=0; $i < sizeof($data); $i++) {
                for ($y=0; $y < sizeof($data[$i]); $y++) {
                    $keyArray[$i][$y] = $data[$i][$y]['key'];
                    $valueArray[$i][$y] = $data[$i][$y]['value'];
                }
            }
            for ($i=0; $i < sizeof($data); $i++) {
                  (new SymfonyStyle($input, $output))
                    ->table(
                        [$keyArray[$i]],
                        [$valueArray[$i]]
                    );
            }
            return 0;
        }
        if (!$resultValid) {
            $symfonyStyle->comment("The query is not valid:");
            return 1;
        }
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
