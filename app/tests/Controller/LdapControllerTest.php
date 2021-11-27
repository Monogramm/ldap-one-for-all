<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * This will suppress all the TooManyPublicMethods
 * PMD warnings in this class.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class LdapControllerTest extends AuthenticatedWebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client = null;

    public $fullDn = 'cn=Hermes Conrad,ou=people,dc=planetexpress,dc=com';
    public $query = '(description=Human)';

    public function setUp(): void
    {
        $this->client = $this->createAuthenticatedClient();
    }

    /**
     * Function test getLdapEntries normal use
     */
    public function testGetLdapEntries()
    {
        $this->client->request(
            'GET',
            '/api/ldap',
            [
                'base'=>$this->fullDn,
                'query'=>$this->query,
                'filters'=>'[]',
                'attributes'=>['dn'],
                'size'=>0,
                'max'=>0
            ]
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $responseEntries = json_decode($responseContent, true);
        $this->assertNotEmpty($responseEntries['items']);
    }

    /**
     * Function test getLdapEntries without parameter
     */
    public function testGetLdapEntriesWithoutParameters()
    {
        $this->client->request('GET', '/api/ldap', []);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $responseEntries = json_decode($responseContent, true);
        $this->assertNotEmpty($responseEntries['items']);
    }

    /**
     * Function test getLdapEntries Entry doesn't exist
     */
    public function testGetLdapEntriesNoEntry()
    {
        $this->client->request(
            'GET',
            '/api/ldap',
            [
                'base'=>'not-exist',
                'query'=>'',
                'filters'=>'[]',
                'attributes'=>['dn'],
                'size'=>0,
                'max'=>0
            ]
        );

        $this->assertSame(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Function test getLdapEntries Error
     */
    public function testGetLdapEntriesWError()
    {
        $this->client->request('GET', '/api/ldap', ['base'=>'empty',]);

        $this->assertSame(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Function test getLdapEntryByDn normal use
     */
    public function testGetLdapEntryByDn()
    {
        $this->client->request('GET', "/api/ldap/$this->fullDn");

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $responseEntries = json_decode($responseContent, true);
        $this->assertCount(2, $responseEntries);
        $this->assertNotEmpty($responseEntries['attributes']);
    }

    /**
     * Function test getLdapEntryByDn Entry doesn't exist
     */
    public function testGetLdapEntryByDnNoEntry()
    {
        $param = 'not-exist';
        $this->client->request('GET', "/api/ldap/$param");
        $this->assertSame(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Function test createLdapEntry normal use
     */
    public function testCreateLdapEntry()
    {
        $contentUrl = '{
            "dn":"cn=Cubert Farnsworth,ou=people,dc=planetexpress,dc=com",
            "attributes":{
                "sn": ["Farnsworth"],
                "objectClass": ["inetOrgPerson"]
            }
        }';

        $this->client->request('POST', '/api/admin/ldap', [], [], [], $contentUrl);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Function test createLdapEntry throw Ldap error Empty entry
     */
    public function testCreateLdapEntryEmptyEntry()
    {
        $contentUrl = '{
            "dn":"",
            "attributes":{}
        }';

        $this->client->request('POST', '/api/admin/ldap', [], [], [], $contentUrl);
        $this->assertSame(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Function test editLdapEntry normal use
     */
    public function testEditLdapEntry()
    {
        $contentUrl = '{
            "dn":"cn=Hermes Conrad,ou=people,dc=planetexpress,dc=com",
            "attributes":{
                "sn": ["Con"],
                "objectClass": ["inetOrgPerson"],
                "description": ["Decapodian"]
            }
        }';

        $this->client->request('PUT', "/api/admin/ldap/$this->fullDn", [], [], [], $contentUrl);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($responseContent);
    }

    /**
     * Function test editLdapEntry using query
     */
    public function testEditLdapEntryByQuery()
    {
        $contentUrl = '{
            "dn":"cn=Hermes Conrad,ou=people,dc=planetexpress,dc=com",
            "attributes":{
                "sn": ["Con"],
                    "objectClass": ["inetOrgPerson"],
                    "description": ["Decapodian"]
            }
        }';

        $this->client->request('PUT', "/api/admin/ldap/$this->fullDn", ['query'=>$this->query], [], [], $contentUrl);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertNotEmpty($responseContent);
    }

    /**
     * Function test editLdapEntry Entry to update don't exist
     */
    public function testEditLdapEntryNoEntry()
    {
        $param = 'not-exist';
        $contentUrl = '{
            "dn":"not-exist",
            "attributes":{
                "sn": ["Con"],
                    "objectClass": ["inetOrgPerson"],
                    "description": ["Decapodian"]
            }
        }';

        $this->client->request('PUT', "/api/admin/ldap/$param", ['query'=> $this->query], [], [], $contentUrl);
        $this->assertSame(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Function test deleteLdapEntry normal use
     */
    public function testDeleteLdapEntry()
    {
        $this->client->request('DELETE', "/api/admin/ldap/$this->fullDn");
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals([], $responseContent);
    }

    /**
     * Function test deleteLdapEntry delete non existing entry
     */
    public function testDeleteLdapEntryNotExisting()
    {
        $this->client->request('DELETE', '/api/admin/ldap/not-exist');
        $this->assertSame(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * Function test patchLdapEntry modify one or more attribute
     */
    public function testPatchLdapEntry()
    {
        $contentUrl = '{
            "dn":"cn=Hermes Conrad,ou=people,dc=planetexpress,dc=com",
            "attributes":{
                "description": ["Human", "Jamaican"]
            }
        }';

        $this->client->request('PATCH', "/api/admin/ldap/$this->fullDn", [], [], [], $contentUrl);
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($responseContent);
    }
}
