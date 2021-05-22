<?php

namespace App\Tests\Controller;

use App\Entity\Parameter;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class ParameterControllerTest extends AuthenticatedWebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client = null;

    public function setUp(): void
    {
        $this->client = $this->createAuthenticatedClient();
    }

    public function testGetTypes()
    {
        $this->client->request('GET', '/api/admin/parameter/types');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(3, count($content));
    }

    public function testGetAll()
    {
        $this->client->request('GET', '/api/admin/parameter', ['page'=>0, 'size'=>0]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(3, $content['total']);
    }

    public function testGetAllPaginated()
    {
        $this->client->request('GET', '/api/admin/parameter', ['page'=>1, 'size'=>20]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(3, $content['total']);
    }

    public function testCrud()
    {
        $parameter = [
            'name'  => 'DUMMY_PARAMETER',
            'type'  => Parameter::STRING_TYPE,
            'value' => '42',
        ];

        // Create
        $createPayload = json_encode($parameter);
        $this->client->request('POST', '/api/admin/parameter', [], [], [], $createPayload);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $createContent = $this->client->getResponse()->getContent();
        $createParameter = json_decode($createContent, true);
        $this->assertNotEmpty($createParameter['id']);
        $this->assertSame($parameter['name'], $createParameter['name']);
        $this->assertSame($parameter['value'], $createParameter['value']);
        $this->assertSame($parameter['type'], $createParameter['type']);

        // Update
        $parameter['id'] = $createParameter['id'];
        $parameter['description'] = 'The anwser to life, the Universe and everything';
        $updatePayload = json_encode($parameter);
        $this->client->request(
            'PUT',
            '/api/admin/parameter/' . $parameter['id'],
            [],
            [],
            [],
            $updatePayload
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $updateContent = $this->client->getResponse()->getContent();
        $updateParameter = json_decode($updateContent, true);
        $this->assertNotEmpty($updateParameter['id']);
        $this->assertSame($parameter['description'], $updateParameter['description']);

        // Delete
        $this->client->request(
            'DELETE',
            '/api/admin/parameter/' . $parameter['id'],
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCrudSecret()
    {
        $parameter = [
            'name'  => 'DUMMY_PARAMETER',
            'type'  => Parameter::SECRET_TYPE,
            'value' => '42',
        ];

        // Create
        $createPayload = json_encode($parameter);
        $this->client->request('POST', '/api/admin/parameter', [], [], [], $createPayload);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $createContent = $this->client->getResponse()->getContent();
        $createParameter = json_decode($createContent, true);
        $this->assertNotEmpty($createParameter['id']);
        $this->assertSame($parameter['name'], $createParameter['name']);
        $this->assertEmpty($createParameter['value']);
        $this->assertSame($parameter['type'], $createParameter['type']);

        // Update
        $parameter['id'] = $createParameter['id'];
        $parameter['description'] = 'The anwser to life, the Universe and everything';
        $updatePayload = json_encode($parameter);
        $this->client->request(
            'PUT',
            '/api/admin/parameter/' . $parameter['id'],
            [],
            [],
            [],
            $updatePayload
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $updateContent = $this->client->getResponse()->getContent();
        $updateParameter = json_decode($updateContent, true);
        $this->assertNotEmpty($updateParameter['id']);
        $this->assertSame($parameter['description'], $updateParameter['description']);

        // Delete
        $this->client->request(
            'DELETE',
            '/api/admin/parameter/' . $parameter['id'],
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
