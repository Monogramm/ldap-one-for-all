<?php

namespace App\DataFixtures;

use App\Entity\BackgroundJob;
use App\Entity\Currency;
use App\Entity\Media;
use App\Entity\Parameter;
use App\Entity\User;
use App\Entity\VerificationCode;
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

        $currencyEuro = new Currency();
        $currencyEuro
            ->setName('Euro')
            ->setIsoCode('EUR')
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
        ;
        $manager->persist($currencyEuro);

        $parameters = array(
            array( 'name' => 'APP_PUBLIC_URL', 'value' => 'http://localhost:8000', 'type' => 'string', 'description' => 'Public URL for backend generated links'),
            array( 'name' => 'APP_SUPPORT_EMAIL', 'value' => 'support@yopmail.com', 'type' => 'string', 'description' => 'Support email address which will receive technical notifications'),
            array( 'name' => 'APP_REGISTRATION_ENABLED', 'value' => '1', 'type' => 'string', 'description' => 'Enable/disable user registration. Allowed values are 1 (enabled) and 0 (disabled).'),
            array( 'name' => 'LDAP_USER_DEFAULT_ROLE', 'value' => 'ROLE_USER', 'type' => 'string', 'description' => 'LDAP default role on first login'),
            array( 'name' => 'LDAP_GROUP_ADMIN', 'value' => 'cn=admin_staff,ou=people,dc=planetexpress,dc=com', 'type' => 'string', 'description' => 'LDAP Group DN associated to Administrator role (ROLE_ADMIN)'),
            array( 'name' => 'LDAP_GROUP_USER', 'value' => 'cn=ship_crew,ou=people,dc=planetexpress,dc=com', 'type' => 'string', 'description' => 'LDAP Group DN associated to User role (ROLE_USER)'),
        );

        foreach ($parameters as $param) {
            $parameter = new Parameter();
            $parameter
                ->setName($param['name'])
                ->setValue($param['value'])
                ->setCreatedAt(Carbon::now('UTC'))
                ->setUpdatedAt(Carbon::now('UTC'))
            ;
            if (isset($param['type'])) {
                $parameter->setType($param['type']);
            }
            if (isset($param['description'])) {
                $parameter->setDescription($param['description']);
            }
            $manager->persist($parameter);
        }

        $user = new User();
        $user
            ->setUsername('username')
            ->setEmail('firstname.lastname@yopmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->verify()
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
        ;
        $password = $this->encoder->encodePassword($user, 'pa$$word');
        $user->setPassword($password);
        $manager->persist($user);

        $verificationCode = new VerificationCode();
        $verificationCode
            ->setCode('9P8O7I6U')
            ->setUser($user)
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
        ;
        $manager->persist($verificationCode);

        $media = new Media();
        $media
            ->setName('DummyMedia.png')
            ->setFilename('DummyMedia123456789.png')
            ->setDescription('Test Media')
            ->setType('image/png')
            ->setCreatedAt(Carbon::now('UTC'))
            ->setUpdatedAt(Carbon::now('UTC'))
        ;
        $manager->persist($media);

        $manager->flush();
    }
}
