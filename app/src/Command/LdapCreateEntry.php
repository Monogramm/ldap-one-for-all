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
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

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
                'dn',
                InputArgument::REQUIRED,
                'LDAP entry Distinguished Name'
            )
            ->addArgument(
                'attr',
                InputArgument::REQUIRED,
                'LDAP entry attributes. Must be provided as a valid JSON string: {"uid":"john.doe","cn":"John DOE"}'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dn = $input->getArgument('dn');
        $attributes = $input->getArgument('attr');

        $symfonyStyle = new SymfonyStyle($input, $output);

        $jsonDecodeAttributes = json_decode($attributes, true);

        if ($jsonDecodeAttributes==null) {
            $symfonyStyle->error("The Attribute argument is not a valid JSON.");
            return 1;
        }

        if ($this->client->create($dn, $jsonDecodeAttributes)) {
            $symfonyStyle->success('Following LDAP entry was successfuly create');
            return 0;
        }
        
        $symfonyStyle->error("An error occurred during creation of LDAP entry");
        return 1;
    }

    protected function isValid(SymfonyStyle $ioStyle, $dn): bool
    {
        if (empty($dn)&& is_string($dn)) {
            $ioStyle->error('Username cannot be empty');
            return false;
        }
        return true;
    }
}
