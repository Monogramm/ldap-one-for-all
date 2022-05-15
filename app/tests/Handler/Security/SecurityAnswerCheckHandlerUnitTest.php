<?php

namespace App\Tests\Handler\Security;

use App\Entity\SecurityAnswer;
use App\Entity\SecurityQuestion;
use App\Entity\User;
use App\Exception\User\EmailAlreadyTaken;
use App\Exception\User\UsernameAlreadyTaken;
use App\Handler\Security\SecurityAnswerChangeHandler;
use App\Handler\Security\SecurityAnswerCheckHandler;
use App\Repository\SecurityAnswerRepository;
use App\Repository\SecurityQuestionRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityAnswerCheckHandlerUnitTest extends TestCase
{
    public function testCheckUserSecurityAnswer()
    {
        $securityQuestionRepositoryMock = $this->getMockBuilder(SecurityQuestionRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $securityQuestion = (new SecurityQuestion())
            ->setName('FIRST_CAR_BRAND')
            ->setI18n([
                'en' => 'What is the brand of your first car?',
                'fr' => 'Quelle est la marque de votre premier véhicule ?'
            ])
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $user = (new User())
            ->setUsername('firstname.lastname')
            ->setPassword('firstname.lastname@yopmail.com')
            ->setEmail('S&cur3P@ssW0rd')
            ->setLanguage('en');

        $answer = 'raw answer';
        $encodedAnswer = '**************';
        $securityAnswer = (new SecurityAnswer())
            ->setUser($user)
            ->setAnswer($encodedAnswer)
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
        ;
        $securityQuestion
            ->addSecurityAnswer($securityAnswer)
        ;

        $securityQuestionRepositoryMock->expects($this->never())
            ->method('findOneBy')
            ->willReturn($securityQuestion);

        $passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn($encodedAnswer);

        $securityAnswerRepositoryMock = $this->getMockBuilder(SecurityAnswerRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $securityAnswerRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($securityAnswer);

        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalClone()
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $userRepositoryMock->expects($this->never())
            ->method('findOneBy')
            ->willReturn($user);

        $handler = new SecurityAnswerCheckHandler(
            $securityQuestionRepositoryMock,
            $passwordEncoderMock,
            $securityAnswerRepositoryMock,
            $userRepositoryMock
        );

        $answerValid = $handler->checkUserSecurityAnswer($user, $securityQuestion, $answer);

        // Check that users cannot force their status or role through API
        $this->assertNotNull($answerValid);
        $this->assertTrue($answerValid);
    }
}
