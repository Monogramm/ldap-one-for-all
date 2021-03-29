<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LdapController extends AbstractController
{
    /**
     * @Route("/api/ldap/info/", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getData(string $identfiant): JsonResponse
    {
        return new JsonResponse(['info'=>'eeee']);
    }

    /**
     * @Route("/api/ldap/create/", methods={"POST"})
     *
     * @return Response
     */
    public function create(): Response
    {
        return new Response();
    }

    /**
     * @Route("/api/ldap/entry/", methods={"PUT"})
     *
     * @return JsonResponse
     */
    public function modify(): JsonResponse
    {
        return new JsonResponse([]);
    }
}