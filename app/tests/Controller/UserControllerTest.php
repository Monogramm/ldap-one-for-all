<?php

namespace App\Tests\Controller;

use App\Entity\VerificationCode;
use App\Entity\User;
use phpseclib\Crypt\Random;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class UserControllerTest extends AuthenticatedWebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client = null;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetAll()
    {
        $this->authenticateClient($this->client);

        $this->client->request('GET', '/api/admin/user', ['page'=>0, 'size'=>0]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(1, $content['total']);
    }

    public function testGetAllPaginated()
    {
        $this->authenticateClient($this->client);

        $this->client->request('GET', '/api/admin/user', ['page'=>1, 'size'=>20]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(1, $content['total']);
    }

    public function testCrud()
    {
        $testUuid = Uuid::uuid4();
        $user = [
            'username' => 'john.doe.' . $testUuid,
            'email'    => 'john.doe.' . $testUuid . '@yopmail.com',
            'password' => 'password',
            'language' => 'en',
        ];

        // Create
        $createPayload = json_encode($user);
        $this->client->request('POST', '/api/user', [], [], [], $createPayload);

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $createContent = $this->client->getResponse()->getContent();
        $createUser = json_decode($createContent, true);
        $this->assertEmpty($createUser);

        // TODO Test async message is sent on user creation
        ///**
        // * @var InMemoryTransport $transport
        // */
        //$transport = self::$container->get('messenger.transport.async');
        //$this->assertCount(1, $transport->getSent());

        // Authenticate with created user
        $this->authenticateClient(
            $this->client,
            $user['username'],
            $user['password']
        );

        // Get current user
        $this->client->request(
            'GET',
            '/api/user'
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $getContent = $this->client->getResponse()->getContent();
        $getUser = json_decode($getContent, true);
        $this->assertSame($user['username'], $getUser['username']);
        $this->assertSame($user['email'], $getUser['email']);
        $this->assertSame(false, $getUser['isVerified']);
        $this->assertSame($user['language'], $getUser['language']);

        // Verify current user (retrieve user and verification code from DB)
        /**
         * @var User
         */
        $userEntity = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $user['username']])
        ;
        $this->assertNotEmpty($userEntity->getId());
        /**
         * @var VerificationCode
         */
        $verificationCode = $this->entityManager
            ->getRepository(VerificationCode::class)
            ->findOneBy(['user' => $userEntity->getId()])
        ;
        $verifyCode = [
            'code' => $verificationCode->getCode(),
        ];
        $verifyPayload = json_encode($verifyCode);
        $this->client->request(
            'POST',
            '/api/user/verify',
            [],
            [],
            [],
            $verifyPayload
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $verifyContent = $this->client->getResponse()->getContent();
        $this->assertSame('[]', $verifyContent);

        // Get current user after verification
        $this->client->request(
            'GET',
            '/api/user'
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $getContent = $this->client->getResponse()->getContent();
        $getUser = json_decode($getContent, true);
        $this->assertSame($user['username'], $getUser['username']);
        $this->assertSame($user['email'], $getUser['email']);
        $this->assertSame(true, $getUser['isVerified']);
        $this->assertSame($user['language'], $getUser['language']);

        // Disable current user
        $this->client->request(
            'PUT',
            '/api/user/disable'
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $disableContent = $this->client->getResponse()->getContent();
        $this->assertSame('[]', $disableContent);
    }

    public function testAdminCrud()
    {
        $this->authenticateClient($this->client);

        $testUuid = Uuid::uuid4();
        $user = [
            'username' => 'john.doe.' . $testUuid,
            'email'    => 'john.doe.' . $testUuid . '@yopmail.com',
            'password' => 'password',
            'language' => 'en',
        ];

        // Create
        $createPayload = json_encode($user);
        $this->client->request('POST', '/api/user', [], [], [], $createPayload);

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $createContent = $this->client->getResponse()->getContent();
        $createUser = json_decode($createContent, true);
        $this->assertEmpty($createUser);

        // TODO Update
        /*
        $user['id'] = $createUser['id'];
        $user['language'] = 'fr';
        $updatePayload = json_encode($user);
        $this->client->request(
            'PUT',
            '/api/user/' . $user['id'],
            [],
            [],
            [],
            $updatePayload
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $updateContent = $this->client->getResponse()->getContent();
        $updateUser = json_decode($updateContent, true);
        $this->assertNotEmpty($updateUser['id']);
        $this->assertSame($user['language'], $updateUser['language']);
        */

        // TODO Delete
        /*
        $this->client->request(
            'DELETE',
            '/api/user/' . $user['id'],
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        */
    }
}
