<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Exception\InvalidArgumentException;

trait BuildLdapConfig
{
    public function configureLdapOptions($commande): void
    {
        $commande->addOption(
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

    /**
     * @return array
     **/
    public function returnConfig(InputInterface $input)
    {
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

        return $config;
    }
}
