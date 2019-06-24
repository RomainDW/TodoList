<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\UnitTestCase;

class HomeControllerTest extends UnitTestCase
{
    public function testSecure()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testLoggedIn()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Bienvenue sur Todo List', $crawler->filter('h1')->text());
    }
}
