<?php

namespace App\Tests\Command;

use App\Command\ParameterSetCommand;
use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use App\Service\Encryptor;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ParameterSetCommandUnitTest extends KernelTestCase
{
    public function testExecute()
    {
        /** @var ParameterRepository|MockObject $parameterRepository */
        $parameterRepository = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $name = 'DUMMY';
        $value = 'test-value';
        $type = 'string';
        $desc = 'This is a test parameter';

        $parameterRepository->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturn(null);

        /** @var EntityManager|MockObject $emi */
        $emi = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $encryptor = new Encryptor('12345678901234567890123456789012');

        $cmd = new ParameterSetCommand(
            $emi,
            $parameterRepository,
            $encryptor
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => $name,
            'value' => $value,
            '--type' => $type,
            '--desc' => $desc
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }

    public function testExecuteInvalidUnknownType()
    {
        /** @var ParameterRepository|MockObject $parameterRepository */
        $parameterRepository = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $name = 'DUMMY';
        $value = 'test-value';
        $type = 'unknown';
        $desc = 'This is a test parameter';

        $parameterRepository->expects($this->exactly(0))
            ->method('findOneBy')
            ->willReturn(null);

        /** @var EntityManager|MockObject $emi */
        $emi = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $encryptor = new Encryptor('12345678901234567890123456789012');

        $cmd = new ParameterSetCommand(
            $emi,
            $parameterRepository,
            $encryptor
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => $name,
            'value' => $value,
            '--type' => $type,
            '--desc' => $desc
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(1, $code);
    }

    public function testExecuteInvalidEmpty()
    {
        /** @var ParameterRepository|MockObject $parameterRepository */
        $parameterRepository = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $name = '';
        $value = '';
        $type = '';
        $desc = '';

        $parameterRepository->expects($this->exactly(0))
            ->method('findOneBy')
            ->willReturn(null);

        /** @var EntityManager|MockObject $emi */
        $emi = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $encryptor = new Encryptor('12345678901234567890123456789012');

        $cmd = new ParameterSetCommand(
            $emi,
            $parameterRepository,
            $encryptor
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => $name,
            'value' => $value,
            '--type' => $type,
            '--desc' => $desc
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(1, $code);
    }

    public function testExecuteInvalidNumber()
    {
        /** @var ParameterRepository|MockObject $parameterRepository */
        $parameterRepository = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $name = 'DUMMY';
        $value = 'test-value';
        $type = 'number';
        $desc = 'This is a test parameter';

        $parameterRepository->expects($this->exactly(0))
            ->method('findOneBy')
            ->willReturn(null);

        /** @var EntityManager|MockObject $emi */
        $emi = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $encryptor = new Encryptor('12345678901234567890123456789012');

        $cmd = new ParameterSetCommand(
            $emi,
            $parameterRepository,
            $encryptor
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => $name,
            'value' => $value,
            '--type' => $type,
            '--desc' => $desc
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(1, $code);
    }

    public function testExecuteSecret()
    {
        /** @var ParameterRepository|MockObject $parameterRepository */
        $parameterRepository = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $name = 'DUMMY';
        $value = 'test-value';
        $type = 'secret';
        $desc = 'This is a test parameter';
    
        $parameter = (new Parameter())
            ->setName($name)
            ->setValue('initial-value')
            ->setType('string')
            ->setDescription('Initial description');

        $parameterRepository->expects($this->exactly(1))
            ->method('findOneBy')
            ->willReturn($parameter);

        /** @var EntityManager|MockObject $emi */
        $emi = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $encryptor = new Encryptor('12345678901234567890123456789012');

        $cmd = new ParameterSetCommand(
            $emi,
            $parameterRepository,
            $encryptor
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:parameters:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => $name,
            'value' => $value,
            '--type' => $type,
            '--desc' => $desc
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }
}
