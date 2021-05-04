<?php

namespace App\Tests\Command;

use App\Command\LdapUpdateEntryCommand;
use App\Tests\Command\AbstractUnitTestLdap;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Console\Exception\RuntimeException;

class LdapUpdateEntryCommandUnitTest extends AbstractUnitTestLdap
{

    public $query = '(cn=Hermes Conrad)';
    public $attribute = '{"description":["Decapodian"]}';

    public function testExecute()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapQueryMock->expects($this->any())
            ->method('execute')
            ->willReturn([
                new Entry(
                    "dn=cn=Hermes Conrad,ou=people,dc=planetexpress,dc=com",
                    [
                        'objectClass' => ['inetOrgPerson'],
                        'cn' => ['Hermes Conrad'],
                        'sn' => ['Conrad'],
                        'description' => ['Human'],
                        'employeeType' => ['Bureaucrat', 'Accountant'],
                        'givenName' => ['Hermes'],
                        'mail' => ['hermes@planetexpress.com'],
                        'ou' => ['Office Management'],
                        'uid' => ['hermes'],
                        'userPassword' => ['hermes']
                    ]
                )
            ]);

        $this->ldapAdapterMock->expects($this->once())
            ->method('createQuery')
            ->willReturn($this->ldapQueryMock);

        $this->ldapConnectionMock->expects($this->once())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->once())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapEntryManagerMock->expects($this->any())
            ->method('update')
            ->willReturn(null);

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapUpdateEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:update-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query' => $this->query,
            'attr' => $this->attribute
        ]);

        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $this->assertEquals(0, $code);
    }

    public function testExecuteWithEmptyQuery()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapQueryMock->expects($this->any())
            ->method('execute')
            ->will($this->throwException(new LdapException));

        $this->ldapConnectionMock->expects($this->once())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->once())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapAdapterMock->expects($this->once())
            ->method('createQuery')
            ->willReturn($this->ldapQueryMock);

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapUpdateEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $this->expectException(LdapException::class);

        $command = $application->find('app:ldap:update-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query' => '',
            'attr' => $this->attribute
        ]);
    }

    public function testExecuteWithoutAttribute()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapConnectionMock->expects($this->never())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->never())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->never())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapAdapterMock->expects($this->never())
            ->method('createQuery')
            ->willReturn($this->ldapQueryMock);

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapUpdateEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $this->expectException(RuntimeException::class);

        $command = $application->find('app:ldap:update-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query' => $this->query,
        ]);
    }

    public function testExecuteWithoutQuery()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapConnectionMock->expects($this->never())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->never())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->never())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapAdapterMock->expects($this->never())
            ->method('createQuery')
            ->willReturn($this->ldapQueryMock);

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapUpdateEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $this->expectException(RuntimeException::class);

        $command = $application->find('app:ldap:update-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'attr' => $this->attribute
        ]);
    }

    public function testExecuteWithoutQueryAndAttribute()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapConnectionMock->expects($this->never())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->never())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->never())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapAdapterMock->expects($this->never())
            ->method('createQuery')
            ->willReturn($this->ldapQueryMock);

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapUpdateEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $this->expectException(RuntimeException::class);

        $command = $application->find('app:ldap:update-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
    }
}
