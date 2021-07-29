<?php

namespace App\Health\Checks;

use App\Health\Health;
use App\Health\HealthIndicator;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * A HealthIndicator that checks a Messenger component is reachable.
 */
class MessengerHealthIndicator extends HealthIndicator
{

    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public function getHealth(bool $includeDetails = false): Health
    {
        $health = new Health(Health::UNKNOWN);

        // TODO Check messenger service is reachable or at least setup
        if (empty($this->bus)) {
            $health->down();
        } else {
            $health->up();
        }

        if ($includeDetails === true) {
            // TODO If successful, provide "transports" details
        }

        return $health;
    }
}
