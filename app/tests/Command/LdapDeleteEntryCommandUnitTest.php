<?php

namespace App\Tests\Command;

use App\Command\LdapDeleteEntryCommand;
use App\Tests\Command\AbstractUnitTestLdap;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Console\Exception\RuntimeException;

class LdapDeleteEntryCommandUnitTest extends AbstractUnitTestLdap
{

    public $fullDn = 'cn=Hubert J. Farnsworth,ou=people,dc=planetexpress,dc=com';

    public function testExecute()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapConnectionMock->expects($this->once())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->once())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapEntryManagerMock->expects($this->any())
            ->method('remove')
            ->willReturn(true);

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapDeleteEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:delete-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'dn' => $this->fullDn
        ]);

        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $this->assertEquals(0, $code);
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

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapDeleteEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $this->expectException(RuntimeException::class);

        $command = $application->find('app:ldap:delete-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
    }

    public function testExecuteWithEmptyDn()
    {
        $this->buildLdapMock();

        $this->ldapConnectionMock->expects($this->exactly(0))
            ->method('isBound')
            ->willReturn(true);

        $this->ldapConnectionMock->expects($this->once())
            ->method('bind');

        $this->ldapAdapterMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->ldapConnectionMock);

        $this->ldapAdapterMock->expects($this->once())
            ->method('getEntryManager')
            ->willReturn($this->ldapEntryManagerMock);

        $this->ldapEntryManagerMock->expects($this->any())
            ->method('remove')
            ->will($this->throwException(
                new LdapException()
            ));

        $ldap = new Ldap($this->ldapAdapterMock);

        $cmd = new LdapDeleteEntryCommand(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $this->expectException(LdapException::class);

        $command = $application->find('app:ldap:delete-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'dn' => ''
        ]);
    }
}
