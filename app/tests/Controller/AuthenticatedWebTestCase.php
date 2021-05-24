<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * AuthenticatedWebTestCase is the base class for functional tests which require authentication.
 */
class AuthenticatedWebTestCase extends WebTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username username for authentication.
     * @param string $password password for authentication.
     *
     * @return KernelBrowser
     */
    protected function createAuthenticatedClient($username = 'username', $password = 'pa$$word')
    {
        $client = static::createClient();
        $this->authenticateClient($client, $username, $password);

        return $client;
    }

    /**
     * Authenticate a client with a default Authorization header.
     *
     * @param KernelBrowser $client client to authenticate.
     * @param string $username username for authentication.
     * @param string $password password for authentication.
     */
    protected function authenticateClient(KernelBrowser $client, $username = 'username', $password = 'pa$$word'): void
    {
        $client->request(
            'POST',
            '/api/login',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array(
                'username' => $username,
                'password' => $password,
            ))
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['token']);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }
}
