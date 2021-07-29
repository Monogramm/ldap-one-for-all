<?php

namespace App\Health\Checks;

use App\Health\Health;
use App\Health\HealthIndicator;
use Symfony\Component\Mailer\MailerInterface;

/**
 * A HealthIndicator for configured smtp server(s).
 */
class MailHealthIndicator extends HealthIndicator
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function getHealth(bool $includeDetails = false): Health
    {
        $health = new Health(Health::UNKNOWN);

        // TODO Check mail service is reachable or at least setup
        if (empty($this->mailer)) {
            $health->down();
        } else {
            $health->up();
        }

        if ($includeDetails === true) {
            // TODO If successful, provide "location" hostname
        }

        return $health;
    }
}
