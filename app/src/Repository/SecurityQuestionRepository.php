<?php

namespace App\Repository;

use App\Entity\SecurityQuestion;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Security Question Entity Repository.
 *
 * @method SecurityQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityQuestionRepository extends AbstractServiceEntityRepository
{
    /**
     * Simplified constructor (for autowiring).
     *
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecurityQuestion::class);
    }

    public function findByName(string $name): ?SecurityQuestion
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param array $names Array of names requested.
     *
     * @return SecurityQuestion[]
     *
     * @psalm-return array<array-key, SecurityQuestion>
     */
    public function findAllByNames(array $names): array
    {
        return $this->findBy(['name' => $names]);
    }
}
