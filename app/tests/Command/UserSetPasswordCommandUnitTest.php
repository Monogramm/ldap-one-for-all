<?php

namespace App\Tests;

use App\Command\UserSetPasswordCommand;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PasswordGenerator;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSetPasswordCommandUnitTest extends KernelTestCase
{
    public function testExecute()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        $username = 'firstname.lastname';
        $email = 'firstname.lastname@yopmail.com';
        $password = 'S&cur3P@ssW0rd';
        $encodedPassword = '**************';
        $user = (new User())
            ->setUsername($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setLanguage('en');

        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($user);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $passwordGeneratorMock = $this->createMock(PasswordGenerator::class);

        $passwordGeneratorMock->expects($this->exactly(0))
            ->method('generate')
            ->willReturn($password);

        $cmd = new UserSetPasswordCommand(
            $emMock,
            $userRepositoryMock,
            $passwordEncoderMock,
            $passwordGeneratorMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:set-password');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
            '--password' => $password,
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }

    public function testExecuteInvalid()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        $username = '';
        $password = '';
        $encodedPassword = '';

        $userRepositoryMock->expects($this->exactly(0))
            ->method('findOneBy')
            ->willReturn(null);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $passwordGeneratorMock = $this->createMock(PasswordGenerator::class);

        $passwordGeneratorMock->expects($this->once())
            ->method('generate')
            ->willReturn($password);

        $cmd = new UserSetPasswordCommand(
            $emMock,
            $userRepositoryMock,
            $passwordEncoderMock,
            $passwordGeneratorMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:set-password');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
        ], [
            'password' => $password,
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(1, $code);
    }

    public function testExecuteConflict()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        $username = 'firstname.lastname';
        $email = 'firstname.lastname@yopmail.com';
        $password = 'S&cur3P@ssW0rd';
        $encodedPassword = '**************';

        $userRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $passwordGeneratorMock = $this->createMock(PasswordGenerator::class);

        $passwordGeneratorMock->expects($this->exactly(0))
            ->method('generate')
            ->willReturn($password);

        $cmd = new UserSetPasswordCommand(
            $emMock,
            $userRepositoryMock,
            $passwordEncoderMock,
            $passwordGeneratorMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:set-password');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
            '--password' => $password,
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(1, $code);
    }
}
