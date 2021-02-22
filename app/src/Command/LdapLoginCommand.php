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

class LdapLoginCommand extends Command
{
    protected static $defaultName = 'app:ldap:login';

    /**
     * @var Ldap
     */
    private $ldap;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Ldap $ldap,
        LoggerInterface $logger
    ) {
        $this->ldap = $ldap;
        $this->logger = $logger;
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
            ->setDescription('Test login against current LDAP server')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'LDAP User ID'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'LDAP User Password'
            )
            ->addOption(
                'uid-key',
                null,
                InputOption::VALUE_REQUIRED,
                'LDAP username key. If not set, will retrieve value of env var LDAP_AUTH_USERNAME_ATTRIBUTE'
            )
            ->addOption(
                'mail-key',
                null,
                InputOption::VALUE_REQUIRED,
                'LDAP mail key. If not set, will retrieve value of env var LDAP_AUTH_EMAIL_ATTRIBUTE'
            )
            ->addOption(
                'base-dn',
                null,
                InputOption::VALUE_REQUIRED,
                'LDAP Base DN. If not set, will retrieve value of env var LDAP_AUTH_BASE_DN'
            )
            ->addOption(
                'query',
                null,
                InputOption::VALUE_REQUIRED,
                'LDAP search query. If not set, will retrieve value of env var LDAP_AUTH_USER_QUERY'
            )
            ->addOption(
                'search-dn',
                null,
                InputOption::VALUE_REQUIRED,
                'LDAP search DN. If not set, will retrieve value of env var LDAP_BIND_DN'
            )
            ->addOption(
                'search-password',
                null,
                InputOption::VALUE_REQUIRED,
                'LDAP search password. If not set, will retrieve value of env var LDAP_BIND_SECRET'
            );
        ;
    }

    /**
     * Returns the option value for a given option name or returns the value of an environment variable.
     *
     * @param string $input The command input
     * @param string $name The option name
     * @param string $env The environment variable name
     *
     * @return string|string[]|bool|null The option value
     *
     * @throws InvalidArgumentException When option given doesn't exist
     */
    private function getOptionOrEnvVar(InputInterface $input, $name, $env)
    {
        $option = $input->getOption($name);
        if (empty($option)) {
            $option = getenv($env);
        }
        return $option;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment("LDAP login:");

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        // Checking input format
        $invalid = false;
        if (empty($username)) {
            $io->error('Username cannot be empty');
            $invalid = true;
        }
        if (empty($password)) {
            $io->error('Password cannot be empty');
            $invalid = true;
        }

        if ($invalid) {
            return 2;
        }

        // Creating LDAP config
        $uidKey = $this->getOptionOrEnvVar($input, 'uid-key', 'LDAP_AUTH_USERNAME_ATTRIBUTE');
        $mailKey = $this->getOptionOrEnvVar($input, 'mail-key', 'LDAP_AUTH_EMAIL_ATTRIBUTE');
        $query = $this->getOptionOrEnvVar($input, 'query', 'LDAP_AUTH_USER_QUERY');

        $baseDn = $this->getOptionOrEnvVar($input, 'base-dn', 'LDAP_AUTH_BASE_DN');
        $searchDn = $this->getOptionOrEnvVar($input, 'search-dn', 'LDAP_BIND_DN');
        $searchPassword = $this->getOptionOrEnvVar($input, 'search-password', 'LDAP_BIND_SECRET');

        $config = [
            'uid_key' => $uidKey,
            'mail_key' => $mailKey,
            'base_dn' => $baseDn,
            'is_ad' => false,
            'ad_domain' => '',
            'query' => $query,
            'search_dn' => $searchDn,
            'search_password' => $searchPassword
        ];
        $ldapClient = new Client($this->ldap, $config);

        // LDAP login
        try {
            $entry = $ldapClient->check($username, $password);
        } catch (\Throwable $e) {
            $io->error('Failed to check against LDAP. Error message: '.$e->getMessage());
            return 1;
        }

        if (!isset(
            $entry->getAttribute($config['mail_key'])[0],
            $entry->getAttribute($config['uid_key'])[0]
        )) {
            $io->error('Failed to authenticate user against LDAP.');
            return 1;
        }
        $io->success('LDAP login successful!');

        return 0;
    }
}
