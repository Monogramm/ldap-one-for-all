<?php

namespace App\Command;

use App\Service\Ldap\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapCreateEntry extends Command
{
    protected static $defaultName = 'app:ldap:create-entry';

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
            ->setDescription('Creates a ldap Entrie')
            ->setHelp('Create a new entry in the LDAP using a DN and attributes.')
            ->addArgument(
                'distingName',
                InputArgument::REQUIRED,
                'LDAP entry Distinguished Name'
            )
            ->addArgument(
                'attr',
                InputArgument::REQUIRED,
                'LDAP entry attributes. Must be provided as a valid JSON string: {"uid":"john.doe","cn":"John DOE"}'
            )->addOption(
                'jsonfiles',
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to the json files containing the LDAP entry attributes. Must be provided as a valid JSON file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $distingName = $input->getArgument('distingName');
        $attributes = $input->getArgument('attr');

        $symfonyStyle = new SymfonyStyle($input, $output);

        $jsonDecodeAttributes = json_decode($attributes, true);

        if (is_array($jsonDecodeAttributes) && empty($jsonDecodeAttributes) || $jsonDecodeAttributes==null) {
            $symfonyStyle->error('The Attribute argument is not a valid JSON.');
            return 1;
        }

        if ($this->client->create($distingName, $jsonDecodeAttributes)) {
            $symfonyStyle->success("Following LDAP entry was successfuly create: $distingName");
            return 0;
        }
        
        $symfonyStyle->error('An error occurred during creation of LDAP entry');
        return 1;
    }

    protected function isValid(SymfonyStyle $ioStyle, $distingName): bool
    {
        if (empty($distingName)&& is_string($distingName)) {
            $ioStyle->error('Username cannot be empty');
            return false;
        }
        return true;
    }
}
