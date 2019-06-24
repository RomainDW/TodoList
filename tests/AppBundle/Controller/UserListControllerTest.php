<?php


namespace Tests\AppBundle\Controller;

use Tests\AppBundle\UnitTestCase;

class UserListControllerTest extends UnitTestCase
{
    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/users');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/users');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_ADMIN"
        $this->login(['ROLE_ADMIN']);
        $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testPageRendering()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(1, $crawler->filter('.table')->count());
    }
}
