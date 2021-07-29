<?php

namespace App\Health\Checks;

use App\Health\Health;
use App\Health\HealthIndicator;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;

/**
 * A HealthIndicator for configured LDAP server(s).
 */
class LdapHealthIndicator extends HealthIndicator
{

    private LdapInterface $ldap;
    /**
     * @var array
     */
    private $ldapConfig;

    public function __construct(
        Ldap $ldap,
        array $ldapConfig
    ) {
        $this->ldap = $ldap;
        $this->ldapConfig = $ldapConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getHealth(bool $includeDetails = false): Health
    {
        $health = new Health(Health::UNKNOWN);

        // Check LDAP service is reachable or at least setup
        $entryManager = $this->ldap->getEntryManager();
        if (empty($entryManager) || empty($this->ldapConfig)) {
            $health->down();
        } else {
            $health->up();
        }
 
        if ($includeDetails === true) {
            // TODO If successful (and possible), provide "location" hostname
        }

        return $health;
    }
}
