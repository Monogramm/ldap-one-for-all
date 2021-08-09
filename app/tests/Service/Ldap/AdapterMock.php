<?php

namespace App\Tests\Service\Ldap;

use Symfony\Component\Ldap\Adapter\AdapterInterface;
use Symfony\Component\Ldap\Adapter\ConnectionInterface;
use Symfony\Component\Ldap\Adapter\EntryManagerInterface;
use Symfony\Component\Ldap\Exception\LdapException;

/**
 * This will suppress all the PMD warnings in
 * this class.
 *
 * @SuppressWarnings(PHPMD)
 */
class AdapterMock implements AdapterInterface
{
    private $config;
    private $connection;
    private $entryManager;

    public function __construct(array $config = [])
    {
        if (!\extension_loaded('ldap')) {
            throw new LdapException('The LDAP PHP extension is not enabled.');
        }

        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new ConnectionMock($this->config);
        }

        return $this->connection;
    }

    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntryManager()
    {
        if (null === $this->entryManager) {
            $this->entryManager = new EntryManagerMock($this->getConnection());
        }

        return $this->entryManager;
    }

    public function setEntryManager(EntryManagerInterface $entryManager)
    {
        $this->entryManager = $entryManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($distinguishedNames, $query, array $options = [])
    {
        return new QueryMock($this->getConnection(), $distinguishedNames, $query, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function escape($subject, $ignore = '', $flags = 0)
    {
        $value = ldap_escape($subject, $ignore, $flags);

        // Per RFC 4514, leading/trailing spaces should be encoded in DNs, as well as carriage returns.
        if ((int) $flags & \LDAP_ESCAPE_DN) {
            if (!empty($value) && ' ' === $value[0]) {
                $value = '\\20'.substr($value, 1);
            }
            if (!empty($value) && ' ' === $value[\strlen($value) - 1]) {
                $value = substr($value, 0, -1).'\\20';
            }
            $value = str_replace("\r", '\0d', $value);
        }

        return $value;
    }
}
