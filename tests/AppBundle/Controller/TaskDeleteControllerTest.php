<?php


namespace Tests\AppBundle\Controller;

use Tests\AppBundle\UnitTestCase;

class TaskDeleteControllerTest extends UnitTestCase
{
    public function testSecure()
    {
        //Method not allowed
        $this->client->request('GET', '/tasks/1/delete');
        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        //User not logged
        $this->client->request('POST', '/tasks/1/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "IS_AUTHENTICATED_ANONYMOUSLY"
        $this->login(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $this->client->request('POST', '/tasks/1/delete');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testTaskNotFound()
    {
        $this->logIn();
        $this->client->request('POST', '/tasks/999/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }
}
