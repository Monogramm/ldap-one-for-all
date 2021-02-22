<?php

namespace App\Repository;

use App\Entity\PasswordResetCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordResetCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordResetCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordResetCode[]    findAll()
 * @method PasswordResetCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordResetCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordResetCode::class);
    }

    // /**
    //  * @return PasswordResetCode[] Returns an array of PasswordResetCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordResetCode
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
