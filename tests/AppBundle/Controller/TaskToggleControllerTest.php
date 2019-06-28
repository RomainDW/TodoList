<?php


namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\MyTestCase;

class TaskToggleControllerTest extends MyTestCase
{
    public function testSecure()
    {
        //Method not allowed
        $this->client->request('GET', '/tasks/1/toggle');
        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        //User not logged
        $this->client->request('POST', '/tasks/1/toggle');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "IS_AUTHENTICATED_ANONYMOUSLY"
        $this->login(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $this->client->request('POST', '/tasks/1/toggle');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testTaskNotFound()
    {
        $this->logIn();
        $this->client->request('POST', '/tasks/999/toggle');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testTaskToggleTrue()
    {
        $id = $this->task->getId();
        // Init task is not done
        $this->task->toggle(false);
        $this->logIn();
        $this->client->request('POST', '/tasks/'.$id.'/toggle');

        // Assert that the task is now done
        $this->assertTrue($this->task->isDone());
        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertEquals(Response::HTTP_FOUND, $statusCode);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertEquals(Response::HTTP_OK, $statusCode);
        $this->assertContains('Superbe ! La tâche '.$this->task->getTitle().' a bien été marquée comme faite.', $crawler->filter('div.alert.alert-success')->text());
    }

    public function testTaskToggleFalse()
    {
        $id = $this->task->getId();
        // Init task is done
        $this->task->toggle(true);
        $this->logIn();
        $this->client->request('POST', '/tasks/'.$id.'/toggle');

        // Assert that the task is now not done
        $this->assertFalse($this->task->isDone());
        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertEquals(Response::HTTP_FOUND, $statusCode);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertEquals(Response::HTTP_OK, $statusCode);
        $this->assertContains('Superbe ! La tâche '.$this->task->getTitle().' a bien été marquée comme non terminée.', $crawler->filter('div.alert.alert-success')->text());
    }
}
