<?php

namespace App\Tests\Repository;

use App\Entity\BackgroundJob;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BackgroundJobRepositoryTest extends KernelTestCase
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

    public function testSearchByName()
    {
        /**
         * @var BackgroundJob
         */
        $backgroundJob = $this->entityManager
            ->getRepository(BackgroundJob::class)
            ->findOneBy(['name' => 'Fixture success job'])
        ;

        $this->assertNotNull($backgroundJob);
        $this->assertSame('success', $backgroundJob->getStatus());
    }

    public function testFindAllByPage()
    {
        /**
         * @var Paginator
         */
        $backgroundJobs = $this->entityManager
            ->getRepository(BackgroundJob::class)
            ->findAllByPage(1, 10)
        ;

        $this->assertSame(2, $backgroundJobs->count());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
