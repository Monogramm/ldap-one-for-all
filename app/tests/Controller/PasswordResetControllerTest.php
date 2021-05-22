<?php

namespace App\Tests\Controller;

use App\Entity\PasswordResetCode;
use App\Repository\PasswordResetCodeRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetControllerTest extends AuthenticatedWebTestCase
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

    public function testCreatePasswordResetCode()
    {
        // Create Password Reset code for fixture user
        $user = [
            'email'    => 'firstname.lastname@yopmail.com',
        ];

        $createPayload = json_encode($user);
        $this->client->request('POST', '/api/password/reset', [], [], [], $createPayload);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $createContent = $this->client->getResponse()->getContent();
        $createPasswordResetCode = json_decode($createContent, true);
        $this->assertEmpty($createPasswordResetCode);

        // Reset user password using latest code generated
        /**
         * @var PasswordResetCodeRepository
         */
        $passwordResetCodeRepository = $this->entityManager
            ->getRepository(PasswordResetCode::class);
        /**
         * @var Paginator
         */
        $passwordResetCodes = $passwordResetCodeRepository->findAllByPage(1, 1, [], ['createdAt'=>'DESC']);
        $this->assertNotEmpty($passwordResetCodes);

        /**
         * @var PasswordResetCode
         */
        $passwordResetCode = $passwordResetCodes->getIterator()->current();
        $this->assertNotEmpty($passwordResetCode);
        $this->assertNotEmpty($passwordResetCode->getCode());

        $this->client->request(
            'GET',
            '/api/password/reset/' . $passwordResetCode->getCode()
        );

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // XXX Is there a way to test authentication with new password?
    }
}
