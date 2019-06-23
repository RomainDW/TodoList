<?php


namespace Tests\AppBundle\Controller;

use Tests\AppBundle\UnitTestCase;

class TaskEditControllerTest extends UnitTestCase
{
    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/tasks/1/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "IS_AUTHENTICATED_ANONYMOUSLY"
        $this->login(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $this->client->request('GET', '/tasks/1/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/tasks/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testFormRendering()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/1/edit');

        $this->assertEquals(1, $crawler->filter('form')->count());
    }
}
