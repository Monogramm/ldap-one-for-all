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
        int $page,
        int $size,
        ?array $filters = [],
        ?array $orders = null
    ): Paginator {
        $offset = ($page - 1) * $size;
        $alias = 'p';

        $query = $this->createQueryBuilder($alias)
            ->setFirstResult($offset)
            ->setMaxResults($size);

        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (!empty($field)) {
                    $parameters = [];

                    if ($value === null) {
                        $query->andWhere($alias . '.' . $field . ' IS NULL');
                    } elseif (is_array($value)) {
                        $query->andWhere($alias . '.' . $field . ' IN :' . $field);
                        $parameters[$field] = $value;
                    } elseif (is_string($value)) {
                        $query->andWhere($alias . '.' . $field . ' LIKE :' . $field);
                        $parameters[$field] = '%' . $value . '%';
                    } else {
                        $query->andWhere($alias . '.' . $field . ' = :' . $field);
                        $parameters[$field] = $value;
                    }

                    $query->setParameters($parameters);
                }
            }
        }

        if (!empty($orders)) {
            foreach ($orders as $sort => $order) {
                if (!empty($sort)) {
                    $query->addOrderBy($alias . '.' . $sort, $order);
                }
            }
        }

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
