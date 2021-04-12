<?php

namespace App\Command;

use App\Service\Ldap\Client;
use Laminas\Code\Generator\DocBlock\Tag\VarTag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LdapSearchEntry extends Command
{
    protected static $defaultName = 'app:ldap:search-entries';

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
            ->setDescription('Search LDAP Entries')
            ->setHelp('Search entries in the LDAP using a query.')
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

        $ldapEntries = $this->client->search($query);

        if (sizeof($labels) != sizeof($attributes)) {
            $labels = $attributes;
        }

        array_unshift($labels, 'dn');
        
        foreach ($ldapEntries as $key => $entry) {
            $entryDn = $entry->getDn();
            $entries[$key]['dn'] = $entryDn;

            foreach ($attributes as $attr) {
                if ($entry->hasAttribute($attr) && !empty($entry->hasAttribute($attr))) {
                    //Possibility to change json_encode by implode(', ', $var);
                    $entries[$key][$attr] = json_encode($entry->getAttribute($attr));
                } else {
                    $entries[$key][$attr] = "null";
                }
            }
        }
        
        (new SymfonyStyle($input, $output))
            ->table($labels, $entries);
        
        return 0;
    }
}
