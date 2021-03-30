<?php

namespace App\Tests\Handler;

use App\Entity\User;
use App\Exception\User\EmailAlreadyTaken;
use App\Exception\User\UsernameAlreadyTaken;
use App\Handler\UserRegistrationHandler;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegistrationHandlerUnitTest extends TestCase
{
    public function testHandle()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findAllByEmail', 'findAllByUsername'])
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
            ->method('findAllByEmail')
            ->willReturn([]);

        $userRepositoryMock->expects($this->once())
            ->method('findAllByUsername')
            ->willReturn([]);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $emMock->expects($this->once())
            ->method('persist');

        $emMock->expects($this->once())
            ->method('flush');

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $handler = new UserRegistrationHandler(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock
        );

        $handler->handle($user);
    }

    public function testHandleEmailAlreadyTaken()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findAllByEmail', 'findAllByUsername'])
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
            ->method('findAllByEmail')
            ->willReturn([$user]);

        $userRepositoryMock->expects($this->exactly(0))
            ->method('findAllByUsername')
            ->willReturn([]);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $emMock->expects($this->exactly(0))
            ->method('persist');

        $emMock->expects($this->exactly(0))
            ->method('flush');

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $handler = new UserRegistrationHandler(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock
        );

        try {
            $handler->handle($user);
            $this->fail('Exception not thrown on handling user registration');
        } catch (EmailAlreadyTaken $e) {
            $this->assertTrue(true);
        }
    }

    public function testHandleUsernameAlreadyTaken()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['findAllByEmail', 'findAllByUsername'])
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
            ->method('findAllByEmail')
            ->willReturn([]);

        $userRepositoryMock->expects($this->once())
            ->method('findAllByUsername')
            ->willReturn([$user]);

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush'])
            ->getMock();

        $emMock->expects($this->exactly(0))
            ->method('persist');

        $emMock->expects($this->exactly(0))
            ->method('flush');

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $handler = new UserRegistrationHandler(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock
        );

        try {
            $handler->handle($user);
            $this->fail('Exception not thrown on handling user registration');
        } catch (UsernameAlreadyTaken $e) {
            $this->assertTrue(true);
        }
    }
}
