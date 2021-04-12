<?php

namespace App\Tests\Command;

use App\Command\ParameterListCommand;
use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ParameterListCommandUnitTest extends KernelTestCase
{

    public function testExecute()
    {
        $parameterRepositoryMock = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findBy'])
            ->getMock();

        $dateTimeString = Carbon::now('UTC')->toDateString();
        $parts = explode('-', $dateTimeString);
        $parts[2] = --$parts[2];
        $dateTimeString = implode('-', $parts);
        $renewalDate = Carbon::parse($dateTimeString)->setTimezone('UTC');

        $parameterName = 'PARAMETER_NAME';
        $parameterValue = 'Parameter Value';
        $parameter = (new Parameter())
            ->setName($parameterName)
            ->setValue($parameterValue);

        $parameterRepositoryMock->expects($this->once())
            ->method('findBy')
            ->willReturn([$parameter]);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $cmd = new ParameterListCommand(
            $emMock,
            $parameterRepositoryMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }

    public function testExecuteAll()
    {
        $parameterRepositoryMock = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findBy'])
            ->getMock();

        $dateTimeString = Carbon::now('UTC')->toDateString();
        $parts = explode('-', $dateTimeString);
        $parts[2] = --$parts[2];
        $dateTimeString = implode('-', $parts);
        $renewalDate = Carbon::parse($dateTimeString)->setTimezone('UTC');

        $parameterName = 'PARAMETER_NAME';
        $parameterValue = 'Parameter Value';
        $parameter = (new Parameter())
            ->setName($parameterName)
            ->setValue($parameterValue);

        $parameterRepositoryMock->expects($this->once())
            ->method('findBy')
            ->willReturn([$parameter]);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $cmd = new ParameterListCommand(
            $emMock,
            $parameterRepositoryMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--filters' => '{"name": "' . $parameterName . '"}',
            '--orders' => '{"name": "ASC"}',
            '--page' => 0,
            '--size' => 0,
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }
}
