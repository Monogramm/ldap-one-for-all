<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindAllByEmail()
    {
        /**
         * @var UserRepository
         */
        $repository = $this->entityManager
            ->getRepository(User::class)
        ;
        /**
         * @var User[]
         */
        $users = $repository->findAllByEmail('firstname.lastname@yopmail.com');

        $this->assertNotNull($users);
        $this->assertSame(1, count($users));
        $this->assertSame('username', $users[0]->getUsername());
        $this->assertSame('firstname.lastname@yopmail.com', $users[0]->getEmail());
    }

    public function testFindAllByEmailNone()
    {
        /**
         * @var UserRepository
         */
        $repository = $this->entityManager
            ->getRepository(User::class)
        ;
        /**
         * @var User[]
         */
        $users = $repository->findAllByEmail('none@yopmail.com');

        $this->assertNotNull($users);
        $this->assertSame(0, count($users));
    }

    public function testFindAllByUsername()
    {
        /**
         * @var UserRepository
         */
        $repository = $this->entityManager
            ->getRepository(User::class)
        ;
        /**
         * @var User[]
         */
        $users = $repository->findAllByUsername('username');

        $this->assertNotNull($users);
        $this->assertSame(1, count($users));
        $this->assertSame('username', $users[0]->getUsername());
        $this->assertSame('firstname.lastname@yopmail.com', $users[0]->getEmail());
    }

    public function testFindAllByUsernameNone()
    {
        /**
         * @var UserRepository
         */
        $repository = $this->entityManager
            ->getRepository(User::class)
        ;
        /**
         * @var User[]
         */
        $users = $repository->findAllByUsername('none');

        $this->assertNotNull($users);
        $this->assertSame(0, count($users));
    }

    public function testFindAllByPage()
    {
        /**
         * @var UserRepository
         */
        $repository = $this->entityManager
            ->getRepository(User::class)
        ;
        /**
         * @var Paginator
         */
        $users = $repository->findAllByPage(1, 10, ['username' => 'username'], ['username' => 'DESC']);

        $this->assertSame(1, $users->count());
    }

    public function testFindAllLikeUsername()
    {
        /**
         * @var UserRepository
         */
        $repository = $this->entityManager
            ->getRepository(User::class)
        ;
        /**
         * @var User[]
         */
        $users = $repository->findAllLikeUsername('userna');

        $this->assertNotNull($users);
        $this->assertSame('username', $users[0]['username']);
        $this->assertSame('firstname.lastname@yopmail.com', $users[0]['email']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
