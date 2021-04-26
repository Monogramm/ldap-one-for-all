<?php

namespace App\Tests\Command;

use App\Command\LdapCreateEntry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Adapter\AdapterInterface;
use Symfony\Component\Ldap\Adapter\ConnectionInterface;
use Symfony\Component\Ldap\Adapter\QueryInterface;

class LdapCreateEntryCommandUnitTest extends KernelTestCase
{
    public $baseDn = 'ou=people,dc=planetexpress,dc=com';
    public $attribute = '{"sn":["vivi"],"objectClass":["inetOrgPerson"]}';

    public function defautlLdapMock(bool $ldapResponse)
    {
        $ldapQueryMock = $this->getMockBuilder(QueryInterface::class)
        ->disableOriginalClone()
        ->disableProxyingToOriginalMethods()
        ->disableOriginalConstructor()
        ->setMethods(['execute'])
        ->getMock();

        $ldapQueryMock->expects($this->any())
            ->method('execute')
            ->willReturn([
                $ldapResponse,
            ]);

        $ldapAdapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'createQuery', 'getEntryManager', 'escape'])
            ->getMock();

        $ldapConnectionMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['isBound', 'bind'])
            ->getMock();
        $ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);
        $ldapConnectionMock->expects($this->once())
            ->method('bind');

        $ldapAdapterMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($ldapConnectionMock);
        $ldapAdapterMock->expects($this->once())
            ->method('createQuery')
            ->willReturn($ldapQueryMock);
    
        return $ldapAdapterMock;
    }

    public function testExecute()
    {
        $ldapAdapterMock = $this->defautlLdapMock(true);

        $ldap = new Ldap($ldapAdapterMock);
        
        $cmd = new LdapCreateEntry(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();
        
        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:create-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'distingName' => $this->baseDn,
            'attr'=>$this->attribute
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertEquals(true, $output);
    }
}
