<?php

namespace App\Repository;

use App\Entity\BackgroundJob;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackgroundJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackgroundJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackgroundJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackgroundJobRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackgroundJob::class);
    }
}
