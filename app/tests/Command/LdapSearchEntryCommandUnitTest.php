<?php

namespace App\Tests\Command;

use App\Command\LdapSearchEntry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Adapter\AdapterInterface;
use Symfony\Component\Ldap\Adapter\ConnectionInterface;
use Symfony\Component\Ldap\Adapter\QueryInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class LdapSearchEntryCommandUnitTest extends KernelTestCase
{

    public $baseDn = 'ou=people,dc=planetexpress,dc=com';
    public $query = '(cn=Hubert J. Farnsworth)';
    public $attributes = 'uid,email,sn,description';
    public $labels = 'Id,Email,Surname,Type';

    public $uid = ['professor'];
    public $cn = 'cn=Hubert J. Farnsworth';
    public $sn = ['Farnsworth'];
    public $email = ["professor@planetexpress.com","hubert@planetexpress.com"];
    public $description = ['Human'];
    
    public function defautlLdapMock($ldapMockResponse)
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
                $ldapMockResponse,
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
        $ldapAdapterMock = $this->defautlLdapMock(new Entry(
            "dn=$this->cn,$this->baseDn",
                [
                    'Id' => $this->uid,
                    'Email' => $this->email,
                    'Surname' => $this->sn,
                    'Type' => $this->description
                ]
            ));

        $ldap = new Ldap($ldapAdapterMock);

        $cmd = new LdapSearchEntry(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:search-entries');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query'=>$this->query,
            '--attr'=>$this->attributes,
            '--labels'=>$this->labels
        ]);

        // the output of the command in the console
        $code = $commandTester->getStatusCode();
    
        $this->assertEquals(0, $code);
    }

    public function testExecuteWithoutAttribute()
    {
        $ldapAdapterMock = $this->defautlLdapMock(new Entry(
            "dn=$this->cn,$this->baseDn",
                [
                    'Id' => $this->uid,
                    'Email' => $this->email,
                    'Surname' => $this->sn,
                    'Type' => $this->description
                ]
            ));

        $ldap = new Ldap($ldapAdapterMock);

        $cmd = new LdapSearchEntry(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:search-entries');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query'=>$this->query,
            '--labels'=>$this->labels
        ]);

        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $this->assertEquals(0, $code);
    }

    public function testExecuteWithoutLabels()
    {
        $ldapAdapterMock = $this->defautlLdapMock(new Entry(
            "dn=$this->cn,$this->baseDn",
                [
                    'Id' => $this->uid,
                    'Email' => $this->email,
                    'Surname' => $this->sn,
                    'Type' => $this->description
                ]
            ));

        $ldap = new Ldap($ldapAdapterMock);

        $cmd = new LdapSearchEntry(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:search-entries');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query'=>$this->query,
            '--attr'=>$this->attributes
        ]);

        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $this->assertEquals(0, $code);
    }
    
    public function testExecuteWithoutAttributeAndLibelle()
    {
        $ldapAdapterMock = $this->defautlLdapMock(new Entry(
            "dn=$this->cn,$this->baseDn",
                [
                    'Id' => $this->uid,
                    'Email' => $this->email,
                    'Surname' => $this->sn,
                    'Type' => $this->description
                ]
            ));

        $ldap = new Ldap($ldapAdapterMock);

        $cmd = new LdapSearchEntry(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:search-entries');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'query'=>$this->query
        ]);

        // the output of the command in the console
        $code = $commandTester->getStatusCode();
        $this->assertEquals(0, $code);
    }

    public function testExecuteWithoutQuery()
    {
        $this->markTestIncomplete(
            'This test is not finish yet.'
          );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "query").');

        $ldapAdapterMock = $this->defautlLdapMock(new RuntimeException);

        $ldap = new Ldap($ldapAdapterMock);

        $cmd = new LdapSearchEntry(
            $ldap
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:ldap:search-entries');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        /*
        try {
            $commandTester->execute([
                '--attr'=>$this->attributes,
                '--labels'=>$this->labels
            ]);
        } catch (\RuntimeException $e) {
            throw $e;
        }*/
        // the output of the command in the console
        /*$code = $commandTester->getStatusCode();
        $this->assertEquals(1, $code);*/
    }
}
