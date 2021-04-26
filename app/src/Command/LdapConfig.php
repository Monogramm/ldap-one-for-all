<?php

namespace App\Command;

trait LdapConfig
{
    public function returnConfig()
    {
        // Creating LDAP config
        $uidKey = getenv('LDAP_AUTH_USERNAME_ATTRIBUTE');
        $mailKey = getenv('LDAP_AUTH_EMAIL_ATTRIBUTE');
        $queryLdap = getenv('LDAP_AUTH_USER_QUERY');

        $baseDn = getenv('LDAP_AUTH_BASE_DN');
        $searchDn = getenv('search-dn', 'LDAP_BIND_DN');
        $searchPassword = getenv('search-password', 'LDAP_BIND_SECRET');

        $config = [
            'uid_key' => $uidKey,
            'mail_key' => $mailKey,
            'base_dn' => $baseDn,
            'is_ad' => false,
            'ad_domain' => '',
            'query' => $queryLdap,
            'search_dn' => $searchDn,
            'search_password' => $searchPassword
        ];

        return $config;
    }
}
