<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
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

    public function findUsersByEmail(string $email)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

    public function findUsersByUserName(string $username)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }

    public function findByUsernamesWithSelectUsernameAndId(string $username): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.username LIKE :username')
            ->setParameter('username', $username.'%')
            ->getQuery()
            ->getArrayResult();
    }
}
