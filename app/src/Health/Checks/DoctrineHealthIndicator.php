<?php

namespace App\Health\Checks;

use App\Health\Health;
use App\Health\HealthIndicator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * A HealthIndicator that checks available disk space and reports a status of
 * DOWN when it drops below a threshold.
 */
class DoctrineHealthIndicator extends HealthIndicator
{

    private EntityManagerInterface $emi;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
    }

    /**
     * {@inheritdoc}
     */
    public function getHealth(bool $includeDetails = false): Health
    {
        $health = new Health(Health::UNKNOWN);

        $connection = $this->emi->getConnection();
        if (empty($connection)) {
            $health->down();
            $database = false;
        } else {
            $health->up();
            $database = $connection->getDatabasePlatform()->getName();
        }

        if ($includeDetails === true) {
            $health->withDetail('database', $database);
        }

        return $health;
    }
}
