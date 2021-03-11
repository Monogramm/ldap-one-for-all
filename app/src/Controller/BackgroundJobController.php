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
    public function getByPage(
        Request $request,
        BackgroundJobRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $page = (int)$request->get('page', 1);
        $items = (int)$request->get('size', 20);

        $jobs = $repository->findAllByPage($page, $items);

        $data = $serializer->normalize(
            $jobs,
            BackgroundJob::class
        );

        $total = count($jobs);
        return new JsonResponse([
            'total' => $total,
            'items' => $data,
        ]);
    }
}
