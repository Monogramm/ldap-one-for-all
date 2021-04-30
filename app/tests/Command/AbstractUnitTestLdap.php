<?php

namespace App\Tests\Command;

use Symfony\Component\Ldap\Adapter\AdapterInterface;
use Symfony\Component\Ldap\Adapter\ConnectionInterface;
use Symfony\Component\Ldap\Adapter\EntryManagerInterface;
use Symfony\Component\Ldap\Adapter\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractUnitTestLdap extends KernelTestCase
{
    public $ldapAdapterMock;
    public $ldapConnectionMock;
    public $ldapEntryManagerMock;
    public $ldapQueryMock;

    /**
     * Mock the AdapterInterface, EntryManagerInterface, ConnectionInterface interface for the Ldap Class Mock
     *
     * @return Array[QueryInterface,AdapterInterface,EntryManagerInterface,ConnectionInterface]
     */
    public function buildLdapMock()
    {
        $this->ldapQueryMock = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $this->ldapAdapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'createQuery', 'getEntryManager', 'escape'])
            ->getMock();

        $this->ldapEntryManagerMock = $this->getMockBuilder(EntryManagerInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['add','update','rename','remove'])
            ->getMock();

        $this->ldapConnectionMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['isBound', 'bind'])
            ->getMock();

        return
            [
                "queryMock"=>$this->ldapQueryMock,
                "adapterMock"=>$this->ldapAdapterMock,
                "entryManagerMock"=>$this->ldapEntryManagerMock,
                "connectionMock"=>$this->ldapConnectionMock
            ];
    }
}
