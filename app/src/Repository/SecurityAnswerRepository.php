<?php

namespace App\Repository;

use App\Entity\SecurityAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SecurityAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityAnswer[]    findAll()
 * @method SecurityAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecurityAnswer::class);
    }

    public function findAllByUsernameOrEmail(string $username, string $email)
    {
        return $this->createQueryBuilder('sa')
            ->innerJoin('sa.user', 'u')
            ->where('u.username = :username')
            ->orWhere('u.email = :email')
            ->setParameters(['username' => $username, 'email' => $email])
            ->getQuery()
            ->getResult();
    }
}
