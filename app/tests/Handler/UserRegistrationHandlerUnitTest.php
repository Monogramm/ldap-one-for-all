<?php

namespace App\Tests\Handler;

use App\Entity\Parameter;
use App\Entity\User;
use App\Exception\User\EmailAlreadyTaken;
use App\Exception\User\UsernameAlreadyTaken;
use App\Handler\UserRegistrationHandler;
use App\Repository\ParameterRepository;
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
            ->onlyMethods(['findAllByEmail', 'findAllByUsername'])
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

        // Check that users cannot force their status or role through API
        $user->disable(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->verify();

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
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $emMock->expects($this->once())
            ->method('persist');

        $emMock->expects($this->once())
            ->method('flush');

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $parameterRepositoryMock = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findByName'])
            ->getMock();

        $parameterRepositoryMock->expects($this->once())
            ->method('findByName')
            ->willReturn(new Parameter('APP_REGISTRATION_ENABLED', '1'));

        $handler = new UserRegistrationHandler(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock,
            $parameterRepositoryMock
        );

        $saveUser = $handler->handle($user);

        // Check that users cannot force their status or role through API
        $this->assertNotNull($saveUser);
        $this->assertTrue($saveUser->isEnabled());
        $this->assertEquals(['ROLE_USER'], $saveUser->getRoles());
        $this->assertFalse($saveUser->isVerified());
    }

    public function testHandleEmailAlreadyTaken()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findAllByEmail', 'findAllByUsername'])
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
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $emMock->expects($this->exactly(0))
            ->method('persist');

        $emMock->expects($this->exactly(0))
            ->method('flush');

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $parameterRepositoryMock = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findByName'])
            ->getMock();

        $parameterRepositoryMock->expects($this->once())
            ->method('findByName')
            ->willReturn(new Parameter('APP_REGISTRATION_ENABLED', '1'));

        $handler = new UserRegistrationHandler(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock,
            $parameterRepositoryMock
        );

        try {
            $handler->handle($user);
            $this->fail('Exception not thrown on handling invalid user registration (email taken)');
        } catch (EmailAlreadyTaken $e) {
            $this->assertNotNull($e);
        }
    }

    public function testHandleUsernameAlreadyTaken()
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findAllByEmail', 'findAllByUsername'])
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
            ->onlyMethods(['persist', 'flush'])
            ->getMock();

        $emMock->expects($this->exactly(0))
            ->method('persist');

        $emMock->expects($this->exactly(0))
            ->method('flush');

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->exactly(0))
            ->method('encodePassword')
            ->willReturn($encodedPassword);

        $parameterRepositoryMock = $this->getMockBuilder(ParameterRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findByName'])
            ->getMock();

        $parameterRepositoryMock->expects($this->once())
            ->method('findByName')
            ->willReturn(new Parameter('APP_REGISTRATION_ENABLED', '1'));

        $handler = new UserRegistrationHandler(
            $emMock,
            $passwordEncoderMock,
            $userRepositoryMock,
            $parameterRepositoryMock
        );

        try {
            $handler->handle($user);
            $this->fail('Exception not thrown on handling invalid user registration (username taken)');
        } catch (UsernameAlreadyTaken $e) {
            $this->assertNotNull($e);
        }
    }
}
