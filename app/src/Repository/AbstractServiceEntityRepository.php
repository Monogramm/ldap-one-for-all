<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

/**
 * Abstract ServiceEntityRepository class with default pagination, sorting and filtering.
 */
abstract class AbstractServiceEntityRepository extends ServiceEntityRepository
{
    /**
     * Finds all entities in the repository with pagination.
     *
     * @param int    $page    Page number (starting at 1).
     * @param int    $size    Page size.
     * @param array  $criteria The filters expressions.
     * @param array  $orderBy  The ordering expressions.
     *
     * @return Paginator
     *
     * @psalm-return Paginator<mixed>
     */
    public function findAllByPage(
        int $page = 1,
        int $size = 20,
        ?array $criteria = [],
        ?array $orderBy = null
    ): Paginator {
        $queryBuilder = $this->setupQueryBuilder('t', null, $page, $size, $criteria, $orderBy);

        return new Paginator($queryBuilder, true);
    }

    /**
     * Finds all entities in the repository.
     *
     * @param array  $criteria The filters expressions.
     * @param array  $orderBy  The ordering expressions.
     *
     * @return array The entities.
     *
     * @psalm-return array
     */
    public function findAll(
        ?array $criteria = [],
        ?array $orderBy = null
    ) {
        $queryBuilder = $this->setupQueryBuilder('t', null, 0, 0, $criteria, $orderBy);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * Setup a query builder with generic pagination, filtering and sorting options.
     *
     * @param int    $page    Page number (starting at 1).
     * @param int    $size    Page size.
     * @param array  $criteria The filters expressions.
     * @param array  $orderBy  The ordering expressions.
     *
     * @return QueryBuilder The query builder setup.
     *
     * @psalm-return QueryBuilder
     */
    protected function setupQueryBuilder(
        string $alias,
        QueryBuilder $queryBuilder = null,
        int $page = 0,
        int $size = 0,
        ?array $criteria = [],
        ?array $orderBy = null
    ): QueryBuilder {
        if (empty($queryBuilder)) {
            $queryBuilder = $this->createQueryBuilder($alias);
        }

        if ($page > 0 && $size > 0) {
            $offset = ($page - 1) * $size;
            $queryBuilder->setFirstResult($offset)
                ->setMaxResults($size);
        }

        if (!empty($criteria)) {
            foreach ($criteria as $field => $value) {
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

        if (!empty($orderBy)) {
            foreach ($orderBy as $sort => $order) {
                if (!empty($sort)) {
                    $queryBuilder->addOrderBy($alias . '.' . $sort, $order);
                }
            }
        }

        return $queryBuilder;
    }
}
