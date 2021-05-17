<?php

namespace App\Repository;

use App\Entity\Parameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;
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
     * @param int    $page    Page number (starting at 1).
     * @param int    $size    Page size.
     * @param array  $filters The filters expressions.
     * @param array  $orders  The ordering expressions.
     *
     * @return Paginator
     *
     * @psalm-return Paginator<mixed>
     */
    public function findAllByPage(
        int $page = 1,
        int $size = 20,
        ?array $filters = [],
        ?array $orders = null
    ): Paginator {
        $queryBuilder = $this->setupQueryBuilder('p', null, $page, $size, $filters, $orders);

        return new Paginator($queryBuilder, true);
    }

    public function findAll(
        ?array $filters = [],
        ?array $orders = null
    ) {
        $queryBuilder = $this->setupQueryBuilder('p', null, 0, 0, $filters, $orders);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    protected function setupQueryBuilder(
        $alias,
        QueryBuilder $queryBuilder = null,
        int $page = 0,
        int $size = 0,
        ?array $filters = [],
        ?array $orders = null
    ): QueryBuilder {
        if (empty($queryBuilder)) {
            $queryBuilder = $this->createQueryBuilder($alias);
        }

        if ($page > 0 && $size > 0) {
            $offset = ($page - 1) * $size;
            $queryBuilder->setFirstResult($offset)
                ->setMaxResults($size);
        }

        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (empty($field) || is_int($field)) {
                    continue;
                }

                if ($value === null) {
                    $queryBuilder->andWhere($alias . '.' . $field . ' IS NULL');
                } elseif (is_array($value)) {
                    $queryBuilder->andWhere($alias . '.' . $field . ' IN :' . $field);
                    $queryBuilder->setParameter($field, $value);
                } elseif (is_string($value)) {
                    $queryBuilder->andWhere($alias . '.' . $field . ' LIKE :' . $field);
                    $queryBuilder->setParameter($field, '%' . $value . '%');
                } else {
                    $queryBuilder->andWhere($alias . '.' . $field . ' = :' . $field);
                    $queryBuilder->setParameter($field, $value);
                }
            }
        }

        if (!empty($orders)) {
            foreach ($orders as $sort => $order) {
                if (!empty($sort)) {
                    $queryBuilder->addOrderBy($alias . '.' . $sort, $order);
                }
            }
        }

        return $queryBuilder;
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
