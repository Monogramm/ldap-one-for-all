<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Media Entity Repository.
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    /**
     * Simplified constructor (for autowiring).
     *
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    /**
     * @param int $page Page number (starting at 1).
     * @param int $size Page size.
     */
    public function findAllByPage(
        int $page,
        int $size
    ) {
        $offset = ($page - 1) * $size;

        return $this->createQueryBuilder('m')
            ->setFirstResult($offset)
            ->setMaxResults($size)
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $name): ?Media
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @param array $names Array of names requested.
     *
     * @return Media[]
     *
     * @psalm-return array<array-key, Media>
     */
    public function findAllByNames(array $names): array
    {
        return $this->findBy(['name' => $names]);
    }
}
