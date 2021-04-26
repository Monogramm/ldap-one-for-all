<?php

namespace App\Command;

use App\Service\Ldap\Client;
use App\Command\LdapConfig;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapCreateEntry extends Command
{
    protected static $defaultName = 'app:ldap:create-entry';

    use LdapConfig;

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

        // Creating LDAP config
        $uidKey = getenv('LDAP_AUTH_USERNAME_ATTRIBUTE');
        $mailKey = getenv('LDAP_AUTH_EMAIL_ATTRIBUTE');
        $queryLdap = getenv('LDAP_AUTH_USER_QUERY');

        $baseDn = getenv('LDAP_AUTH_BASE_DN');

        $config = [
            'uid_key' => $uidKey,
            'mail_key' => $mailKey,
            'base_dn' => $baseDn,
            'is_ad' => "0",
            'ad_domain' => 'planetexpress.com',
            'query' => $queryLdap,
            'search_dn' => 'cn=admin,dc=planetexpress,dc=com',
            'search_password' => 'GoodNewsEveryone',
            'enabled' => '1'
        ];

        $ldapClient = new Client($this->ldap, $config);
        
        if ($ldapClient->create($distingName, $jsonDecodeAttributes)) {
            $symfonyStyle->success("Following LDAP entry was successfuly create: $distingName");
            return 0;
        }
        
        $symfonyStyle->error('An error occurred during creation of LDAP entry');
        return 1;
    }
}
