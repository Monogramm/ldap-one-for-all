<?php

namespace App\Command;

use App\Service\Ldap\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapUpdateEntry extends Command
{
    protected static $defaultName = 'app:ldap:update-entry';

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
            ->setDescription('Update a ldap Entry')
            ->setHelp('Update an existing entry in the LDAP using a DN and attributes.')
            ->addArgument(
                'dn',
                InputArgument::REQUIRED,
                'LDAP entry Distinguished Name'
            )->addArgument(
                'attr',
                InputArgument::REQUIRED,
                'LDAP entry attributes. Must be provided as a valid JSON string: {"uid":"john.doe","cn":"John DOE"}'
            );
    }

    /**
     * @return int|null
     *
     * @psalm-return 0|1|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $distingName = $input->getArgument('dn');
        $attributes = $input->getOption('attr');
        $symfonyStyle = new SymfonyStyle($input, $output);
      
        $symfonyStyle->comment("update entry :");
        
        $jsonDecodeAttributes = json_decode($attributes, true);
        
        if ($jsonDecodeAttributes===null) {
            $symfonyStyle->error('The attribute Option is not a valid JSON');
            return 1;
        }

        if ($this->client->update($distingName, $jsonDecodeAttributes)) {
            $symfonyStyle->success('Following LDAP entry was successfuly updated');
            return 0;
        }
        $symfonyStyle->error("An error occurred during update of LDAP entry");
    }
}
