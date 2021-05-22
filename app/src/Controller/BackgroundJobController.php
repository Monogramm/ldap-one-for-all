<?php

namespace App\Controller;

use App\Entity\BackgroundJob;
use App\Repository\BackgroundJobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BackgroundJobController extends AbstractController
{
    /**
     * @Route("/api/admin/background-jobs", name="get_background_jobs", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getBackgroundJobs(
        Request $request,
        BackgroundJobRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $page = (int)$request->get('page', 1);
        $itemsPerPage = (int)$request->get('size', 20);

        $filters = json_decode($request->get('filters', '[]'), true);
        $orders  = json_decode($request->get('orders', '{"lastExecution":"DESC"}'), true);

        if ($page > 0 && $itemsPerPage > 0) {
            $jobs = $repository->findAllByPage($page, $itemsPerPage, $filters, $orders);
        } else {
            $jobs = $repository->findAll($filters, $orders);
        }

        $total = count($jobs);
        $results = $serializer->normalize(
            $jobs,
            BackgroundJob::class
        );

        return new JsonResponse([
            'total' => $total,
            'items' => $results,
        ]);
    }
}
