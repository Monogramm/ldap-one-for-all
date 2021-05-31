<?php

namespace App\Tests\Repository;

use App\Entity\Parameter;
use App\Repository\ParameterRepository;
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
         * @var ParameterRepository
         */
        $repository = $this->entityManager
            ->getRepository(Parameter::class)
        ;
        /**
         * @var Parameter
         */
        $parameter = $repository->findByName('APP_PUBLIC_URL');

        $this->assertNotNull($parameter);
        $this->assertSame('http://localhost:8000', $parameter->getValue());
    }

    public function testFindAllByNames()
    {
        /**
         * @var ParameterRepository
         */
        $repository = $this->entityManager
            ->getRepository(Parameter::class)
        ;
        /**
         * @var array
         */
        $parameters = $repository->findAllByNames(['APP_PUBLIC_URL']);

        $this->assertSame(1, count($parameters));
    }

    public function testFindAll()
    {
        /**
         * @var ParameterRepository
         */
        $repository = $this->entityManager
            ->getRepository(Parameter::class)
        ;
        /**
         * @var Parameter[]
         */
        $parameters = $repository->findAll();

        $this->assertSame(3, count($parameters));
    }

    public function testFindAllByName()
    {
        /**
         * @var ParameterRepository
         */
        $repository = $this->entityManager
            ->getRepository(Parameter::class)
        ;
        /**
         * @var Parameter[]
         */
        $parameters = $repository->findAll(['name' => 'APP_PUBLIC_URL'], ['name' => 'DESC']);

        $this->assertSame(1, count($parameters));
    }

    public function testFindAllByPage()
    {
        /**
         * @var ParameterRepository
         */
        $repository = $this->entityManager
            ->getRepository(Parameter::class)
        ;
        /**
         * @var Paginator
         */
        $parameters = $repository->findAllByPage(1, 10);

        $this->assertSame(3, count($parameters));
    }

    public function testFindAllByPageAndName()
    {
        /**
         * @var ParameterRepository
         */
        $repository = $this->entityManager
            ->getRepository(Parameter::class)
        ;
        /**
         * @var Paginator
         */
        $parameters = $repository->findAllByPage(1, 10, ['name' => 'APP_PUBLIC_URL'], ['name' => 'DESC']);

        $this->assertSame(1, count($parameters));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
