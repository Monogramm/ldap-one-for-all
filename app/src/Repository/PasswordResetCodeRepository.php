<?php

namespace App\Repository;

use App\Entity\PasswordResetCode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordResetCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordResetCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordResetCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordResetCodeRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordResetCode::class);
    }
}
