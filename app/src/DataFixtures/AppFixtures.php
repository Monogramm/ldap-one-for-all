<?php

namespace App\DataFixtures;

use App\Entity\BackgroundJob;
use App\Entity\Media;
use App\Entity\Parameter;
use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $backgroundJobS = new BackgroundJob();
        $backgroundJobS
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'));
        $backgroundJobS->init('Fixture success job');
        $backgroundJobS->success();
        $manager->persist($backgroundJobS);

        $backgroundJobE = new BackgroundJob();
        $backgroundJobE
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'));
        $backgroundJobE->init('Fixture error job');
        $backgroundJobE->error();
        $manager->persist($backgroundJobE);

        $parameterAppUrl = new Parameter();
        $parameterAppUrl
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
            ->setName('APP_PUBLIC_URL')
            ->setType(Parameter::STRING_TYPE)
            ->setValue('http://localhost:8000')
        ;
        $manager->persist($parameterAppUrl);

        $parameterSupportEmail = new Parameter();
        $parameterSupportEmail
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
            ->setName('APP_SUPPORT_EMAIL')
            ->setType(Parameter::STRING_TYPE)
            ->setValue('support@yopmail.com')
        ;
        $manager->persist($parameterSupportEmail);

        $parameterLdapDefaultRole = new Parameter();
        $parameterLdapDefaultRole
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
            ->setName('LDAP_USER_DEFAULT_ROLE')
            ->setType(Parameter::STRING_TYPE)
            ->setValue('ROLE_ADMIN')
        ;
        $manager->persist($parameterLdapDefaultRole);

        $user = new User();
        $user
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
            ->setUsername('username')
            ->setEmail('firstname.lastname@yopmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->verify()
        ;
        $password = $this->encoder->encodePassword($user, 'pa$$word');
        $user->setPassword($password);
        $manager->persist($user);

        $media = new Media();
        $media
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
            ->setName('DummyMedia.png')
            ->setFilename('DummyMedia123456789.png')
            ->setType('image/png')
        ;
        $manager->persist($media);

        $manager->flush();
    }
}
