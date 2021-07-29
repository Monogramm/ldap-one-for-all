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
    public function health(
        DiskSpaceHealthIndicator $diskSpace,
        DoctrineHealthIndicator $doctrine,
        LdapHealthIndicator $ldap,
        MailHealthIndicator $mail,
        MessengerHealthIndicator $messenger
    ): JsonResponse {
        $health = new Health(Health::UNKNOWN);

        // Return detailed info if user has ADMIN role
        $includeDetails = $this->isGranted('ROLE_ADMIN');

        // TODO Dynamically load & execute all existing healthchecks
        /**
         * @var HealthIndicator[] $checks
         */
        $checks = [
            'diskSpace' => $diskSpace,
            'doctrine' => $doctrine,
            'ldap' => $ldap,
            'mail' => $mail,
            'messenger' => $messenger,
        ];

        foreach ($checks as $key => $healthcheck) {
            try {
                $checkResults = $healthcheck->getHealth($includeDetails);
            } catch (\Exception $exception) {
                $checkResults = new Health(Health::UNKNOWN);
                $checkResults->withException($exception);
            }

            // Set global health to "minimal" health status
            $health->aggregate($checkResults);

            if ($includeDetails === true) {
                $health->withDetail($key, $checkResults);
            }
        }

        return new JsonResponse($health);
    }
}
