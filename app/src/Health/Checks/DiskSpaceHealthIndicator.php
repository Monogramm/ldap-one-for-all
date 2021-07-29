<?php

namespace App\Health\Checks;

use App\Health\Health;
use App\Health\HealthIndicator;

/**
 * A HealthIndicator that checks available disk space and reports a status of
 * DOWN when it drops below a threshold.
 */
class DiskSpaceHealthIndicator extends HealthIndicator
{

    private float $threshold;

    private string $path;

    public function __construct(float $threshold = 0, string $path = '/')
    {
        $this->threshold = $threshold;
        $this->path = $path;
    }

    public function getThreshold(): float
    {
        return $this->threshold;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getHealth(bool $includeDetails = false): Health
    {
        $health = new Health(Health::UNKNOWN);

        $total = disk_total_space($this->getPath());
        $free = disk_free_space($this->getPath());

        if ($total === false || $free === false || $free < $this->getThreshold()) {
            $health->down();
        } else {
            $health->up();
        }

        if ($includeDetails === true) {
            $health->withDetail('total', $total);
            $health->withDetail('free', $free);
            $health->withDetail('threshold', $this->getThreshold());
        }

        return $health;
    }
}
