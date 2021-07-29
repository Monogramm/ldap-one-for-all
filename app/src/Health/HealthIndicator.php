<?php

namespace App\Health;

/**
 * Abtract class to provide an health indicator.
 */
abstract class HealthIndicator
{
    /**
     * Return an indication of health.
     * @param bool $includeDetails if details should be included or removed
     * @return Health the health
     */
    abstract public function getHealth(bool $includeDetails): Health;

    public function __invoke(): Health
    {
        return $this->getHealth(false);
    }
}
