<?php

namespace App\Tests;

use App\Command\LdapLoginCommand;
use App\Service\Ldap\Client;
use Carbon\Carbon;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Adapter\AdapterInterface;
use Symfony\Component\Ldap\Adapter\ConnectionInterface;
use Symfony\Component\Ldap\Adapter\QueryInterface;

class LdapLoginCommandUnitTest extends KernelTestCase
{
    public function testExecute()
    {
        $baseDn = 'ou=people,dc=ldap,dc=example,dc=com';
        $username = 'firstname.lastname';
        $email = 'firstname.lastname@yopmail.com';
        $password = 'S&cur3P@ssW0rd';
        $ldapQueryMock = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $ldapEntry = new Entry(
            "uid=$username,$baseDn",
            [
                'uid' => [ $username ],
                'mail' => [ $email ],
            ]
        );
        $ldapQueryMock->expects($this->any())
            ->method('execute')
            ->willReturn([
                $ldapEntry,
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
        $ldapAdapterMock->expects($this->any())
            ->method('escape')
            ->willReturn($username);

        $ldap = new Ldap($ldapAdapterMock);

        $logger = $this->createMock(Logger::class);

        $cmd = new LdapLoginCommand(
            $ldap,
            $logger
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:login');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
            'password' => $password,
            '--uid-key' => 'uid',
            '--mail-key' => 'mail',
            '--base-dn' => $baseDn,
            '--query' => '(objectClass=inetOrgPerson)',
            //'--search-dn' => "cn=admin,$baseDn",
            //'--search-password' => 'password',
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }
}
