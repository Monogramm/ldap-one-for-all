<?php

namespace App\Tests\Entity;

use App\Entity\securityAnswer;
use App\Entity\SecurityQuestion;
use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class SecurityAnswerUnitTest extends TestCase
{
    public function testSecurityQuestion()
    {
        $securityQuestion = new SecurityQuestion();

        $user = new User();

        $securityAnswer = (new SecurityAnswer())
            ->setUser($user)
            ->setAnswer('encoded_answer_xxxxxx')
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now())
        ;
        $securityQuestion
            ->addSecurityAnswer($securityAnswer)
        ;

        $this->assertNotNull($securityAnswer->getCreatedAt());
        $this->assertNotNull($securityAnswer->getUpdatedAt());

        $this->assertNotNull($securityAnswer->getUser());
        $this->assertEquals($user, $securityAnswer->getUser());

        $this->assertNotNull($securityAnswer->getQuestion());
        $this->assertEquals($securityQuestion, $securityAnswer->getQuestion());

        $this->assertNotNull($securityAnswer->getAnswer());
        $this->assertEquals('encoded_answer_xxxxxx', $securityAnswer->getAnswer());
    }
}
