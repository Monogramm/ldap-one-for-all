<?php

namespace App\Command;

use App\Service\Ldap\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapDeleteEntry extends Command
{
    protected static $defaultName = 'app:ldap:delete-entry';

    /**
     * @var Client
     */
    private $client;

    public function __construct(
        Client $client
    ) {
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
            ->setDescription('Delete a ldap Entry')
            ->setHelp('Delete an existing entry in the LDAP using a DN.')
            ->addArgument(
                'dn',
                InputArgument::REQUIRED,
                'LDAP entry Distinguished Name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dn = $input->getArgument('dn');
        $symfonyStyle = new SymfonyStyle($input, $output);

        if ($this->client->delete($dn)) {
            $symfonyStyle->success('Following LDAP entry was successfuly create');
            return 0;
        }
        
        $symfonyStyle->error("An error occurred during creation of LDAP entry");
        return 1;
    }
}
