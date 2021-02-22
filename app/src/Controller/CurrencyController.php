<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/api/currency", name="currency", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getCurrencies(
        CurrencyRepository $currencyRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $currencies = $currencyRepository->findAll();

        $total = count($currencies);
        $data = $serializer->normalize(
            $currencies,
            Currency::class
        );

        return new JsonResponse([
            'total' => $total,
            'items' => $data,
        ]);
    }
}
