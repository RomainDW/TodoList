<?php


namespace Tests\AppBundle\Controller;

use Tests\AppBundle\MyTestCase;

class TaskListControllerTest extends MyTestCase
{
    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/tasks');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "IS_AUTHENTICATED_ANONYMOUSLY"
        $this->login(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $this->client->request('GET', '/tasks');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testPagination()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertLessThan(7, $crawler->filter('.thumbnail')->count());
    }

    public function testPageNotFound()
    {
        $this->logIn();
        $this->client->request('GET', '/tasks?page=999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
