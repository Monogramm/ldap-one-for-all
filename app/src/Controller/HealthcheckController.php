<?php

namespace App\Controller;

use App\Health\Checks\DiskSpaceHealthIndicator;
use App\Health\Checks\DoctrineHealthIndicator;
use App\Health\Checks\LdapHealthIndicator;
use App\Health\Checks\MailHealthIndicator;
use App\Health\Checks\MessengerHealthIndicator;
use App\Health\Health;
use App\Health\HealthIndicator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Healthcheck controller. Greatly inspired by Spring Boot Actuator.
 *
 * @link https://codereviewvideos.com/course/beginners-guide-back-end-json-api-front-end-2018/video/healthcheck-raw-symfony-4
 */
class HealthcheckController extends AbstractController
{
    /**
     * @var HealthIndicator[]
     */
    private $indicators;

    public function __construct(
        DiskSpaceHealthIndicator $diskSpace,
        DoctrineHealthIndicator $doctrine,
        LdapHealthIndicator $ldap,
        MailHealthIndicator $mail,
        MessengerHealthIndicator $messenger
    ) {
        // TODO Dynamically load all existing indicators
        /**
         * @var HealthIndicator[] $checks
         */
        $this->indicators = [
            'diskSpace' => $diskSpace,
            'doctrine' => $doctrine,
            'ldap' => $ldap,
            'mail' => $mail,
            'messenger' => $messenger,
        ];
    }

    /**
     * @Route("/ping", name="ping", methods={"GET"},)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse('pong');
    }

    /**
     * @Route("/health", name="health", methods={"GET"},)
     *
     * @return JsonResponse
     */
    public function health(): JsonResponse {
        $health = new Health(Health::UNKNOWN);

        $includeDetails = $this->isGrantedHealthDetails();

        foreach ($this->indicators as $key => $indicator) {
            $indicatorHealth = $this->getHealthCheck($indicator, $includeDetails);

            // Set global health to "minimal" health status
            $health->aggregate($indicatorHealth);

            if ($includeDetails === true) {
                $health->withDetail($key, $includeDetails);
            }
        }

        return new JsonResponse($health);
    }

    /**
     * @Route("/health/{indicator}", name="health_indicator", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function healthIndicator(
        string $indicator
    ): JsonResponse {
        $health = new Health(Health::UNKNOWN);

        if (!isset($this->indicators[$indicator])) {
            return new JsonResponse($health);
        }

        $includeDetails = $this->isGrantedHealthDetails();

        $health = $this->getHealthCheck($this->indicators[$indicator], $includeDetails);
        return new JsonResponse($health);
    }

    private function isGrantedHealthDetails(): bool {
        // Return detailed info if user has ADMIN role
        return $this->isGranted('ROLE_ADMIN');
    }

    private function getHealthCheck(
        HealthIndicator $indicator,
        bool $includeDetails = false
    ): Health {
        try {
            $health = $indicator->getHealth($includeDetails);
        } catch (\Exception $exception) {
            $health = new Health(Health::DOWN);
            $health->withException($exception);
        }
        return $health;
    }
}
