<?php

namespace Qwer\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenControllerTest extends WebTestCase
{

    private $format     = "json";
    private $externalId = 11;
    private $user       = "rasom";
    private $password   = "123";
    
    public function testGetTokenAction()
    {
        $client = static::createClient();

        $parameters = array();

        $auth = new \Qwer\UserBundle\Entity\AuthenticationInfo();
        $auth->setLogin($this->user);
        $auth->setPassword($this->password);
        $serializer = $this->getSerializer();
        
        $data = $serializer->serialize($auth, $this->format);
        $parameters["data"] = $data;
        
        $url = sprintf('/api/tokens/%s.%s', $this->externalId, $this->format);
        $crawler = $client->request('POST', $url);

        $crawler->filter("token");
    }

    /**
     * 
     * @return \JMS\Serializer\Serializer
     */
    private function getSerializer()
    {
        $serializer = self::$kernel->getContainer()->get("jms_serializer");
        
        return $serializer;
    }

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/client/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /client/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'qwer_lottobundle_clienttype[field_name]' => 'Test',
        // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'qwer_lottobundle_clienttype[field_name]' => 'Foo',
        // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

}