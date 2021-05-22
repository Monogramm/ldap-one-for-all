<?php

namespace App\Repository;

use App\Entity\VerificationCode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VerificationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerificationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerificationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerificationCodeRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerificationCode::class);
    }
}
