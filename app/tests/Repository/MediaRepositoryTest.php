<?php

namespace App\Tests\Repository;

use App\Entity\Media;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MediaRepositoryTest extends KernelTestCase
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

    public function testFindByName()
    {
        /**
         * @var Media
         */
        $media = $this->entityManager
            ->getRepository(Media::class)
            ->findByName('DummyMedia.png')
        ;

        $this->assertNotNull($media);
        $this->assertSame('DummyMedia123456789.png', $media->getFilename());
    }

    public function testFindAllByNames()
    {
        /**
         * @var array
         */
        $medias = $this->entityManager
            ->getRepository(Media::class)
            ->findAllByNames(['DummyMedia.png'])
        ;

        $this->assertSame(1, count($medias));
    }

    public function testFindAllByPage()
    {
        /**
         * @var array
         */
        $medias = $this->entityManager
            ->getRepository(Media::class)
            ->findAllByPage(1, 10)
        ;

        $this->assertSame(1, count($medias));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
