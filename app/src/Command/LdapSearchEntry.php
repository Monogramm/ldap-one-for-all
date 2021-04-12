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
                'Attributes to retrieve. Must be provided as a JSON. Example: {"Unique ID":"uid"}',
                '{"Complete Name":"cn"}'
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
        $attributes = $input->getOption('attr');
        
        $arrayAttributes = json_decode($attributes);

        if (empty($query) || !is_string($query)) {
            $symfonyStyle->error("The query is not a valid string.");
            return 1;
        }
        if ($arrayAttributes===null) {
            $symfonyStyle->error("The Option format is not valid JSON format.");
            return 1;
        }
        
        // Get the LDAP informations from the ldap
        $entries = $this->client->search($query);

        $rows = [];
        //Always put DN in labels
        $labels = ['DN'];

        // TODO Build array of attributes labels
        foreach ($arrayAttributes as $attribute => $value) {
            $labels[] = $attribute;
        }
        
        foreach ($entries as $entry) {
            $entryDn = $entry->getDn();
            $entryAttrArray = $entry->getAttributes();

            if (is_array($entryAttrArray)) {
                // Always put DN in rows
                $rows[$entryDn] = [
                    json_encode($entryDn),
                ];
                // Get the ldap entry by the number of attribute given in command
                foreach ($arrayAttributes as $attribute => $value) {
                    //TODO Verify if the entry is not empty if empty return null
                    //TODO Fix the way is handle the verification
                    if (!empty($entryAttrArray[$value])) {
                        $rows[$entryDn][] = json_encode(
                            $entryAttrArray[$value]
                        );
                    } elseif (empty($entryAttrArray[$value])) {
                        $rows[$entryDn][] = "null";
                    }
                }
            }
        }
      
        (new SymfonyStyle($input, $output))
            ->table($labels, $rows);
        
        return 0;
    }
}
