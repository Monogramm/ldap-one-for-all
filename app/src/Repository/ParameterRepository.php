<?php

namespace App\Repository;

use App\Entity\Parameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parameter[]    findAll()
 * @method Parameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parameter::class);
    }

    /**
     * @param int $page Page number (starting at 1).
     * @param int $size Page size.
     *
     * @return Paginator
     *
     * @psalm-return Paginator<mixed>
     */
    public function findAllByPage(
        int $page,
        int $size
    ): Paginator {
        $offset = ($page - 1) * $size;

        $query = $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults($size);

        return new Paginator($query, true);
    }

    public function findByName(string $name): ?Parameter
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param array $names Array of names requested.
     *
     * @return Parameter[]
     *
     * @psalm-return array<array-key, Parameter>
     */
    public function findAllByNames(array $names): array
    {
        return $this->findBy(['name' => $names]);
    }
}
