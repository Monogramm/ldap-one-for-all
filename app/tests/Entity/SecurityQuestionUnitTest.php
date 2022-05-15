<?php

namespace App\Tests\Entity;

use App\Entity\SecurityQuestion;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class SecurityQuestionUnitTest extends TestCase
{
    public function testSecurityQuestion()
    {
        $securityQuestion = (new SecurityQuestion())
            ->setName('FIRST_CAR_BRAND')
            ->setI18n([
                'en' => 'What is the brand of your first car?',
                'fr' => 'Quelle est la marque de votre premier véhicule ?'
            ])
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $this->assertNotNull($securityQuestion->getCreatedAt());
        $this->assertNotNull($securityQuestion->getUpdatedAt());

        $this->assertNotNull($securityQuestion->getName());
        $this->assertEquals('FIRST_CAR_BRAND', $securityQuestion->getName());

        $this->assertNotNull($securityQuestion->getI18n());
        $this->assertEquals(
            'What is the brand of your first car?',
            $securityQuestion->getI18nQuestion('en')
        );
        $this->assertEquals(
            'Quelle est la marque de votre premier véhicule ?',
            $securityQuestion->getI18nQuestion('fr')
        );
    }
}
