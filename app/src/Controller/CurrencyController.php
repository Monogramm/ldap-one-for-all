<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/api/currency", name="get_currencies", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getCurrencies(
        CurrencyRepository $repository,
        Request $request,
        SerializerInterface $serializer
    ): JsonResponse {
        $page = (int) $request->get('page', 0);
        $itemsPerPage = (int) $request->get('size', 0);

        $filters = json_decode($request->get('filters', '[]'), true);
        $orders  = json_decode($request->get('orders', '{"name":"ASC"}'), true);

        if ($page > 0 && $itemsPerPage > 0) {
            $currencies = $repository->findAllByPage($page, $itemsPerPage, $filters, $orders);
        } else {
            $currencies = $repository->findAll($filters, $orders);
        }

        $total = count($currencies);
        $results = $serializer->normalize(
            $currencies,
            Currency::class
        );

        return new JsonResponse([
            'total' => $total,
            'items' => $results,
        ]);
    }

    /**
     * @Route("/api/currency/{currency}", name="get_currency", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getCurrency(
        Currency $currency,
        SerializerInterface $serializer
    ): JsonResponse {
        return JsonResponse::fromJsonString(
            $serializer->serialize($currency, 'json', [AbstractNormalizer::GROUPS => 'default'])
        );
    }
}
