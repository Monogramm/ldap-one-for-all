<?php

namespace App\Command;

use App\Service\Ldap\Client;
use App\Command\BuildLdapConfig;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapUpdateEntryCommand extends Command
{
    protected static $defaultName = 'app:ldap:update-entry';

    use BuildLdapConfig;

    /**
     * @var Ldap
     */
    private $ldap;

    public function __construct(
        Ldap $ldap
    ) {
        $this->ldap = $ldap;
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
            ->setDescription('Update a LDAP Entry')
            ->setHelp('Update an existing entry in the LDAP using a DN and attributes.')
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'LDAP Update query. Must be a valid LDAP search query. Example: (description=Human)'
            )->addArgument(
                'attr',
                InputArgument::REQUIRED,
                'LDAP entry attributes. Must be provided as a valid JSON string: {"uid":"john.doe","cn":"John DOE"}'
            );
        $this->configureLdapOptions($this);
    }

    /**
     * @return int|null
     *
     * @psalm-return 0|1|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fullDn = $input->getArgument('query');
        $attributes = $input->getArgument('attr');
        $symfonyStyle = new SymfonyStyle($input, $output);
      
        $symfonyStyle->comment("update entry :");
        
        $jsonDecodeAttributes = json_decode($attributes, true);
        
        if (empty($jsonDecodeAttributes)) {
            $symfonyStyle->error('The attribute Option is not a valid JSON');
            return 1;
        }

        $config = $this->returnConfig($input);

        $ldapClient = new Client($this->ldap, $config);

        if ($ldapClient->update($fullDn, $jsonDecodeAttributes)) {
            $symfonyStyle->success("Following LDAP entry was successfully updated: $fullDn");
            return 0;
        }
        $symfonyStyle->error("An error occurred during update of LDAP entry");
    }
}
