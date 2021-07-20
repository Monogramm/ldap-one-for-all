<?php

namespace App\Tests\Controller;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class CurrencyControllerTest extends AuthenticatedWebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client = null;

    public function setUp(): void
    {
        $this->client = $this->createAuthenticatedClient();
    }

    public function testGetAll()
    {
        $this->client->request('GET', '/api/currency', ['page'=>0, 'size'=>0]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(1, $content['total']);
    }

    public function testGetAllPaginated()
    {
        $this->client->request('GET', '/api/currency', ['page'=>1, 'size'=>20]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame(1, $content['total']);
    }

    public function testGet()
    {
        $this->client->request('GET', '/api/currency', ['page'=>1, 'size'=>20]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseAllContent = $this->client->getResponse()->getContent();
        $allContent = json_decode($responseAllContent, true);
        $this->assertSame(1, $allContent['total']);

        $currencies = $allContent['items'];
        $currency = $currencies[0];

        // Get
        $this->client->request('GET', '/api/currency/' . $currency['id']);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $content = json_decode($responseContent, true);
        $this->assertSame($currency['id'], $content['id']);
        $this->assertSame($currency['isoCode'], $content['isoCode']);
    }
}
