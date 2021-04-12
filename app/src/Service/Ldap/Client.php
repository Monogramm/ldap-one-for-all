<?php


namespace App\Service\Ldap;

use Error;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Entry;
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

    /**
     * @return Entry[]|\Symfony\Component\Ldap\Adapter\CollectionInterface
     *
     * @psalm-return \Symfony\Component\Ldap\Adapter\CollectionInterface|array<array-key, Entry>
     */
    public function executeQuery(string $query)
    {
        $this->ldap->bind($this->config['search_dn'], $this->config['search_password']);
        return $this->ldap->query($this->config['base_dn'], $query)->execute();
    }

    public function create(string $dn, array $attributes): bool
    {
        // TODO Do not bind inside search (must be done before)
        $this->ldap->bind($this->config['search_dn'], $this->config['search_password']);

        $entry = new Entry($dn, $attributes);
        $entryManager = $this->ldap->getEntryManager();

        return $entryManager->add($entry);
    }

    /**
     * @return bool|null
     */
    public function update(string $query, array $attributes) : bool
    {
        $entryManager = $this->ldap->getEntryManager();

        // Finding and updating an existing entry
        $result = $this->executeQuery($query);

        // FIXME Check result before doing anything on it
        $entry = $result[0];
        if ($entry===null) {
            return false;
        }
        
        foreach ($attributes as $key => $value) {
            $entry->setAttribute($key, $value);
        }
        $entryManager->update($entry);

        return true;
    }

    /**
     * @return boolean
     */
    public function delete(string $dn)
    {
        $this->ldap->bind($this->config['search_dn'], $this->config['search_password']);
        $entryManager = $this->ldap->getEntryManager();

        // Removing an existing entry
        return $entryManager->remove(new Entry($dn));
    }

    /**
     * @var string
     *
     * @return (mixed|string)[][]
     *
     * @psalm-return array{0: array{key: string, value: string}, 1: array{key: string, value: string}, 2: array{key: string, value: string}, 3: array{key: string, value: string}, 4: array{key: string, value: string}, 5: array{key: string, value: string}, 6: array{key: string, value: string}, 7: array{key: string, value: string}, 8: array{key: string, value: string}, 9: array{key: string, value: mixed}}
     */
    public function search(string $query)
    {
        //Symfony base function for fetching ldap
        // TODO Do not bind inside search (must be done before)
        $entries = $this->executeQuery($query);
        return $entries;
    }
}
