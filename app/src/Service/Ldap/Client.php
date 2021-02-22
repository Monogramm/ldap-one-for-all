<?php


namespace App\Service\Ldap;

use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class Client
{
    public const REQUIRED = [
        'uid_key',
        'mail_key',
        'base_dn',
        'is_ad',
        'ad_domain',
        'query',
        'search_dn',
        'search_password'
    ];

    /**
     * @var Ldap
     */
    private $ldap;

    /**
     * @var array
     */
    private $config;

    public function __construct(
        Ldap $ldap,
        array $ldapConfig
    ) {
        foreach (self::REQUIRED as $one) {
            if (!isset($ldapConfig[$one])) {
                throw new \RuntimeException("LDAP required config key was not found: $one");
            }
        }
        $this->ldap = $ldap;
        $this->config = $ldapConfig;
    }

    public function check(
        string $login,
        string $password
    ) {

        $username = $this->ldap->escape($login, '', LdapInterface::ESCAPE_FILTER);
        $query = sprintf(
            '(&(|(%s=%s)(%s=%s))%s)',
            $this->config['uid_key'],
            $username,
            $this->config['mail_key'],
            $username,
            $this->config['query']
        );

        if ($this->config['search_dn']) {
            $this->ldap->bind($this->config['search_dn'], $this->config['search_password']);
            $result = $this->ldap->query($this->config['base_dn'], $query)->execute();
            if (1 !== $result->count()) {
                throw new BadCredentialsException('The presented username is invalid.');
            }

            $dn = $result[0]->getDn();
        } else {
            $username = $this->ldap->escape($login, '', LdapInterface::ESCAPE_DN);
            $dn = sprintf('%s=%s,%s', $this->config['uid_key'], $username, $this->config['base_dn']);
        }

        $this->ldap->bind($dn, $password);
        $result = $this->ldap->query($dn, $query)->execute()[0];

        return $result;
    }
}
