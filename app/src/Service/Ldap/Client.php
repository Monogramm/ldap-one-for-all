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

    public function getLdapEntry($query)
    {
        /*
        $result_query= $this->ldap->query($this->config['base_dn'],$query)->execute();
        return $result_query;
        */

        $entry =
        [
            ['key'=>"objectClass", 'value'=>'inetOrgPerson'],
            ['key'=>"cn", 'value'=>"Hubert J. Farnsworth"],
            ['key'=>"sn",'value'=>"Farnsworth"],
            ['key'=>"description",'value'=>"Human"],
            ['key'=>"displayName",'value'=>"Professor Farnsworth"],
            ['key'=>"employeeType",'value'=>"Owner"],
            ['key'=>"employeeType",'value'=>"Founder"],
            ['key'=>"givenName",'value'=>"Hubert"],
            ['key'=>"mail",'value'=>"professor@planetexpress.com"],
            ['key'=>"query",'value'=>$query]
        ];

        return $entry;
    }

    public function create(string $query)
    {
        /*
        $entryManager = $this->ldap->getEntryManager();
        $entryManager->add($data);*/

        if (gettype($query)=="string") {
            return true;
        } else {
            return false;
        }
    }

    public function update(array $query)
    {
        $entry = $query;

        $entryManager = $this->ldap->getEntryManager();

        // Finding and updating an existing entry
        $queryResult = $this->ldap->query($this->config['base_dn'], $query);
        $result = $queryResult->execute();
        $entry = $result[0];
        if ($entry->getAttribute('phoneNumber')) {
            if ($entry->hasAttribute('contractorCompany')) {
                $entry->setAttribute('email', ['fabpot@symfony.com']);
                $resultUpdate = $entryManager->update($entry);
                if ($resultUpdate) {
                    return true;
                }

                if (!$resultUpdate) {
                    return false;
                }
            }
        } elseif (!$entry->getAttribute('phoneNumber')) {
            return false;
        }
    }
    public function delete($target)
    {
        $entryManager = $this->ldap->getEntryManager($target);

        // Removing an existing entry
        $entryManager->remove(new Entry('cn=Test User,dc=symfony,dc=com'));
    }

    /**
     * @var string
     */
    public function search($query)
    {
        /*
        Symfony base function for fetching ldap
        $query = $ldap->query($query);
        $results = $query->execute()->toArray();
        */
        // ------ HARD DATA FOR TEST
        $rows[0] =
        [
            'query'=>$query
        ];
        $rows[1] =
        [
            'cn'=>"George Pompote ",
            'sn'=>'Pompote',
            'givenName'=>'George',
            'description'=>'lumière',
            'displayName'=>'Geroge le malin',
            'employeeType'=>'Existant',
            'mail'=>"pompote@gmail.com",
            'title'=>'CEO'
        ];
        $rows[2] = [
            'cn'=>"Grisois Margoulin",
            'sn'=>'Margoulin',
            'givenName'=>'Grisois',
            'description'=>'humain',
            'displayName'=>'poussière',
            'employeeType'=>'Existant',
            'mail'=>"grisois@gmail.com"
        ];
        $rows[3] = [
            'cn'=>"Kila Mira",
            'sn'=>'Mira',
            'givenName'=>'Kila',
            'description'=>'lumière',
            'displayName'=>'Muske',
            'employeeType'=>'Existant',
            'mail'=>"kila@gmail.com",
            'ou'=>"Delivering Crew",
            'title'=>'Employee'
        ];

        //Creating an associative array for fetching the data with 'key' and 'value'
        $index = $page  = 0;
        foreach ($rows as $inArray) {
            foreach ($inArray as $key => $value) {
                $assortedLdapEntry[$page][$index++] = ['key'=>$key,'value'=>$value];
            }
            $page++;
            $index = 0;
        }
        return $assortedLdapEntry;
    }
}
