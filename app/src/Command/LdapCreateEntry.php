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
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class LdapCreateEntry extends Command
{
    protected static $defaultName = 'ldap:create:entry';

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
        ->setDescription('Creates a ldap Entrie')
        ->setHelp('This command create a entrie in the ldap using a raw query.')
        ->addArgument(
            'key',
            InputArgument::REQUIRED,
            'key'
        )
        ->addArgument(
            'array',
            InputArgument::REQUIRED,
            'Array');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = $input->getArgument('query');

        $io = new SymfonyStyle($input, $output);

        if($this->isValid($io, $query))
        {
            $io->comment("Entrie create :");
            $result = $this->client->create( $query);
            if($result)
            {
                $io->success('Everything is good ! : '.$result);
                return 0;
            }
            else
            {
                $io->error("Something went wrong... : ".$result);
                return 1;
            }   
        } 
        else
            return 1;
        

        return 0;
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