<?php


namespace Tests\AppBundle\Controller;

use Tests\AppBundle\UnitTestCase;

class SecurityControllerTest extends UnitTestCase
{
    public function testFormRendering()
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testRedirectIfLogged()
    {
        $this->logIn();

        $this->client->request('GET', '/login');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }
}
