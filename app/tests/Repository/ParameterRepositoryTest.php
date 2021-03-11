<?php

namespace App\Tests\Repository;

use App\Entity\Parameter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParameterRepositoryTest extends KernelTestCase
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
         * @var Parameter
         */
        $parameter = $this->entityManager
            ->getRepository(Parameter::class)
            ->findByName('APP_PUBLIC_URL')
        ;

        $this->assertNotNull($parameter);
        $this->assertSame('http://localhost:8000', $parameter->getValue());
    }

    public function testFindAllByNames()
    {
        /**
         * @var array
         */
        $parameters = $this->entityManager
            ->getRepository(Parameter::class)
            ->findAllByNames(['APP_PUBLIC_URL'])
        ;

        $this->assertSame(1, count($parameters));
    }

    public function testFindAllByPage()
    {
        /**
         * @var Paginator
         */
        $parameters = $this->entityManager
            ->getRepository(Parameter::class)
            ->findAllByPage(1, 10)
        ;

        $this->assertSame(1, $parameters->count());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
