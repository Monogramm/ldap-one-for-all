<?php

namespace App\Command;

use App\Service\Ldap\Client;
use App\Command\BuildLdapConfig;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapSearchEntryCommand extends Command
{
    protected static $defaultName = 'app:ldap:search-entries';

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
            ->setDescription('Search LDAP Entries')
            ->setHelp('Search LDAP entries using a query.')
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'LDAP Search query. Must be a valid LDAP search query. Example: (description=Human)'
            )
            ->addOption(
                'attr',
                null,
                InputOption::VALUE_REQUIRED,
                'Attributes to retrieve. Example: uid,sn,cn',
                'cn'
            )
            ->addOption(
                'labels',
                null,
                InputOption::VALUE_OPTIONAL,
                'Labels to show. Example: Unique ID,Last Name,Full Name'
            );
        $this->configureLdapOptions($this);
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->comment("List of entries:");

        $query = $input->getArgument('query');

        $attributes = explode(',', trim($input->getOption('attr')));
        $labels =  explode(',', $input->getOption('labels'));

        $config = $this->returnConfig($input);

        $ldapClient = new Client($this->ldap, $config);

        $ldapEntries = $ldapClient->search($query);

        if (count($labels) !== count($attributes) || empty($labels[0])) {
            $labels = $attributes;
        }

        array_unshift($labels, 'dn');

        foreach ($ldapEntries as $key => $entry) {
            $entryDn = $entry->getDn();
            $entries[$key]['dn'] = $entryDn;

            foreach ($attributes as $attr) {
                $entries[$key][$attr] = ($entry->hasAttribute($attr) && !empty($entry->getAttribute($attr))) ?
                    json_encode($entry->getAttribute($attr)) : "null";
            }
        }

        if (isset($entries)) {
            (new SymfonyStyle($input, $output))
                ->table($labels, $entries);

            return 0;
        }

        $symfonyStyle->error('No matching LDAP entry was found.');
        return 1;
    }
}
