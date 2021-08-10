<?php

namespace App\Controller;

use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use App\Service\Encryptor;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ParameterController extends AbstractController
{
    /**
     * @Route("/api/admin/parameter/types", name="parameter_types", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function parameterTypes(): JsonResponse
    {
        return new JsonResponse(Parameter::types());
    }

    /**
     * @Route("/api/admin/parameter", name="get_parameters", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getParameters(
        ParameterRepository $repository,
        Request $request,
        SerializerInterface $serializer
    ): JsonResponse {
        $page = (int) $request->get('page', 1);
        $itemsPerPage = (int) $request->get('size', 20);

        $filters = json_decode($request->get('filters', '[]'), true);
        $orders  = json_decode($request->get('orders', '{"name":"asc"}'), true);

        if ($page > 0 && $itemsPerPage > 0) {
            $parameters = $repository->findAllByPage($page, $itemsPerPage, $filters, $orders);
        } else {
            $parameters = $repository->findAll($filters, $orders);
        }

        $total = count($parameters);
        $results = $serializer->normalize(
            $parameters,
            Parameter::class
        );

        return new JsonResponse([
            'total' => $total,
            'items' => $results
        ]);
    }

    /**
     * @Route("/api/admin/parameter/{parameter}", name="get_parameter", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getParameterById(
        Parameter $parameter,
        SerializerInterface $serializer,
        Encryptor $encryptor
    ): JsonResponse {
        if ($parameter->isSecret() && $parameter->getValue()) {
            $parameter->setValue(
                $encryptor->decryptText(
                    $parameter->getValue()
                )
            );
        }

        $dto = $serializer->serialize($parameter, 'json');

        return JsonResponse::fromJsonString($dto);
    }

    /**
     * @Route("/api/admin/parameter", name="create_parameter", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function createParameter(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $emi,
        Encryptor $encryptor
    ): JsonResponse {
        $dto = $serializer->deserialize(
            $request->getContent(),
            Parameter::class,
            'json'
        );

        if ($dto->isSecret()) {
            $dto->setValue(
                $encryptor->encryptText(
                    $dto->getValue()
                )
            );
        }

        $emi->persist($dto);
        $emi->flush();

        return JsonResponse::fromJsonString(
            $serializer->serialize($dto, 'json')
        );
    }

    /**
     * @Route("/api/admin/parameter/{parameter}", name="edit_parameter", methods={"PUT"})
     *
     * @return JsonResponse
     */
    public function editParameterById(
        Parameter $parameter,
        EntityManagerInterface $emi,
        Request $request,
        SerializerInterface $serializer,
        Encryptor $encryptor
    ): JsonResponse {
        /**
         * @var Parameter $dto
         */
        $dto = $serializer->deserialize(
            $request->getContent(),
            Parameter::class,
            'json',
            [ AbstractNormalizer::OBJECT_TO_POPULATE => $parameter ]
        );

        if ($dto->isSecret()) {
            $dto->setValue(
                $encryptor->encryptText(
                    $dto->getValue()
                )
            );
        }

        $emi->persist($dto);
        $emi->flush();

        return JsonResponse::fromJsonString(
            $serializer->serialize($dto, 'json')
        );
    }

    /**
     * @Route("/api/admin/parameter/{parameter}", name="delete_parameter", methods={"DELETE"})
     *
     * @return JsonResponse
     */
    public function deleteParameter(
        Parameter $parameter,
        EntityManagerInterface $emi
    ): JsonResponse {
        $emi->remove($parameter);
        $emi->flush();

        return new JsonResponse([]);
    }
}
