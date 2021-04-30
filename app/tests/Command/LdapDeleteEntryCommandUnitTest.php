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

    public $fullDn = 'cn=Maurice M. Farnsworth,ou=people,dc=planetexpress,dc=com';

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
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "dn").');

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
        
        $command = $application->find('app:ldap:delete-entry');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        
        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $this->assertEquals(1, $code);
    }

    public function testExecuteWithEmptyDn()
    {
        $this->expectException(LdapException::class);
        $this->expectExceptionMessage('Could not remove entry "": Server is unwilling to perform');
        
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
            ->will($this->throwException(new LdapException('Could not remove entry "": Server is unwilling to perform"')));

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
            'dn' => ''
        ]);
        
        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $output = $commandTester->getDisplay();
        var_dump($output);
        $this->assertEquals(1, $code);
    }
}
