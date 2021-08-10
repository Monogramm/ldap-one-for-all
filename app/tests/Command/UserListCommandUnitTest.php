<?php

namespace App\Tests\Command;

use App\Command\UserListCommand;
use App\Entity\User;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UserListCommandUnitTest extends KernelTestCase
{
    public function testExecute()
    {
        /** @var UserRepository|MockObject $userRepositoryMock */
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findAll'])
            ->getMock();

        $username = 'firstname.lastname';
        $email = 'firstname.lastname@yopmail.com';
        $user = (new User())
            ->setUsername($username)
            ->setEmail($email)
            ->setLanguage('en');

        $userRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn([$user]);

        /** @var EntityManager|MockObject $emMock */
        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $cmd = new UserListCommand(
            $emMock,
            $userRepositoryMock
        );

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application();
        $application->add($cmd);

        $command = $application->find('app:users:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName()
        ));

        $code = $commandTester->getStatusCode();

        $this->assertEquals(0, $code);
    }
}
