<?php

namespace App\Tests\Command;

use App\Command\UserCreateCommand;
use App\Entity\User;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommandUnitTest extends KernelTestCase
{
    public function testExecute()
    {
        /** @var UserRepository|MockObject $userRepositoryMock */
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $username = 'firstname.lastname';
        $email = 'firstname.lastname@yopmail.com';
        $password = 'S&cur3P@ssW0rd';
        $encodedPassword = '**************';

        $userRepositoryMock->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);

        /** @var EntityManager|MockObject $emMock */
        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $cmd = new UserCreateCommand(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
            'email' => $email,
            'password' => $password,
            '--role' => ['ADMIN'],
            '--verified' => true
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }

    public function testExecuteInvalid()
    {
        /** @var UserRepository|MockObject $userRepositoryMock */
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $username = '';
        $email = '';
        $password = '';
        $encodedPassword = '';

        $userRepositoryMock->expects($this->exactly(0))
            ->method('findOneBy')
            ->willReturn(null);

        /** @var EntityManager|MockObject $emMock */
        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $cmd = new UserCreateCommand(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
            'email' => $email,
            'password' => $password,
            '--role' => ['ADMIN'],
            '--verified' => true
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(1, $code);
    }

    public function testExecuteConflict()
    {
        /** @var UserRepository|MockObject $userRepositoryMock */
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
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

        $userRepositoryMock->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn($user);

        /** @var EntityManager|MockObject $emMock */
        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $cmd = new UserCreateCommand(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'username' => $username,
            'email' => $email,
            'password' => $password,
            '--role' => ['ADMIN'],
            '--verified' => true
        ]);

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }
}
