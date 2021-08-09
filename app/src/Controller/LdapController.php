<?php

namespace App\Controller;

use App\DTO\LdapEntryDTO;
use App\Service\Ldap\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\SerializerErrorRenderer;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class LdapController extends AbstractController
{
    /**
     * @Route("/api/ldap", name="get_ldap_entries", methods={"GET"})
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return JsonResponse
     */
    public function getLdapEntries(
        Client $ldap,
        Request $request,
        SerializerInterface $serializer
    ): JsonResponse {

        $query = $request->get('query', '(objectClass=*)');
        $baseDn = $request->get('base', null);

        $options = [];

        $filters = json_decode($request->get('filters', '[]'), true);
        if (!empty($filters)) {
            $queryFilters = '';
            foreach ($filters as $field => $value) {
                switch ($field) {
                    case 'dn':
                        $queryFilters .= "($value)";
                        break;

                    default:
                        $queryFilters .= "($field=$value)";
                        break;
                }
            }
            $query = "(&$queryFilters$query)";
        }
        // XXX Is there any way to ask LDAP server to sort results?
        //$orders  = json_decode($request->get('orders', null), true);

        // Attributes option.
        $attributes = $request->get('attributes', ['dn']);
        $options['filter'] = $attributes;

        // Add query options.
        $size = (int) $request->get('size', 0);
        $max = (int) $request->get('max', 0);

        $options['pageSize'] = $size;
        $options['maxItems'] = $max;

        try {
            $ldap->bind();
            $ldapEntries = $ldap->search($query, $baseDn, $options);
        } catch (LdapException $exception) {
            return new JsonResponse($exception->getMessage(), 500);
        }
        
        $total = count($ldapEntries);
        $entries = array();

        foreach ($ldapEntries as $key => $ldapEntry) {
            $dto = LdapEntryDTO::fromEntry($ldapEntry);

            $entries[$key]['dn'] = $dto->getDn();
            $entries[$key]['attributes'] = $dto->getAttributes();
        }

        $entries = $serializer->normalize(
            $entries,
            Entry::class
        );

        return new JsonResponse([
            'total' => $total,
            'items' => $entries,
        ]);
    }

    /**
     * @Route("/api/ldap/{fullDN}", name="get_ldap_entry", methods={"GET"})
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return JsonResponse Entry | LdapException
     */
    public function getLdapEntryByDn(
        string $fullDN,
        Client $ldap,
        Request $request,
        SerializerInterface $serializer
    ): JsonResponse {

        $query = $request->get('query', '(objectClass=*)');

        $options = $request->get('options', []);

        try {
            $ldap->bind();
            $ldapEntry = $ldap->get($query, $fullDN, $options);
        } catch (LdapException $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }

        $dto = LdapEntryDTO::fromEntry($ldapEntry);
        $json = $serializer->serialize($dto, 'json');

        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/api/admin/ldap", name="create_ldap_entry", methods={"POST"})
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return JsonResponse
     */
    public function createLdapEntry(
        Client $ldap,
        Request $request,
        SerializerInterface $serializer,
        TranslatorInterface $translator
    ): JsonResponse {

        try {
            $dto = $serializer->deserialize(
                $request->getContent(),
                LdapEntryDTO::class,
                'json'
            );
        } catch (NotEncodableValueException $exception) {
            return new JsonResponse($translator->trans('error.ldap.deserialize'), 400);
        }

        $entry = $dto->toEntry();
        try {
            $ldap->bind();
            $ldap->create($entry->getDn(), $entry->getAttributes());
        } catch (LdapException $exception) {
            return new JsonResponse($exception->getMessage(), 500);
        }
        $returnDto = LdapEntryDTO::fromEntry($entry);
        $json = $serializer->serialize($returnDto, 'json');

        return JsonResponse::fromJsonString(
            $serializer->serialize($json, 'json')
        );
    }

    /**
     * @Route("/api/admin/ldap/{fullDN}", name="edit_ldap_entry", methods={"PUT"})
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return JsonResponse
     */
    public function editLdapEntry(
        string $fullDN,
        Client $ldap,
        Request $request,
        SerializerInterface $serializer,
        TranslatorInterface $translator
    ): JsonResponse {
        $query = $request->get('query', '(objectClass=*)');

        try {
            $dto = $serializer->deserialize(
                $request->getContent(),
                LdapEntryDTO::class,
                'json'
            );
        } catch (NotEncodableValueException $exception) {
            return new JsonResponse($translator->trans('error.ldap.deserialize'), 400);
        }

        $entry = $dto->toEntry();
        try {
            $ldap->bind();
            $ldap->update($fullDN, $query, $entry->getAttributes());
        } catch (LdapException $exception) {
            return new JsonResponse($exception->getMessage(), 500);
        }
        $returnDto = LdapEntryDTO::fromEntry($entry);
        $json = $serializer->serialize($returnDto, 'json');

        return new JsonResponse($json, 200);
    }

    /**
     * @Route("/api/admin/ldap/{fullDn}", name="delete_ldap_entry", methods={"DELETE"})
     *
     * @return JsonResponse
     */
    public function deleteLdapEntry(
        string $fullDn,
        Client $ldap
    ): JsonResponse {
        $status = 400;
        try {
            $ldap->bind();
            $ldap->delete($fullDn);
            $status = 200;
        } catch (LdapException $exception) {
            $exception->getMessage();
            $status = 500;
        }

        return new JsonResponse([], $status);
    }
}
