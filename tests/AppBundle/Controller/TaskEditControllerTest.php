<?php


namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\MyTestCase;

class TaskEditControllerTest extends MyTestCase
{
    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/tasks/'.$this->task->getId().'/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "IS_AUTHENTICATED_ANONYMOUSLY"
        $this->login(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $this->client->request('GET', '/tasks/'.$this->task->getId().'/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/tasks/'.$this->task->getId().'/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testFormRendering()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/'.$this->task->getId().'/edit');

        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testTaskEdit()
    {
        // The task is owned by the admin but it is the user who will modify the task
        $this->assertEquals('admin@email.com', $this->task->getUser()->getEmail());
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'user@email.com']);
        $this->logIn(['ROLE_USER', 'ROLE_ADMIN'], $user);
        $crawler = $this->client->request('GET', '/tasks/'.$this->task->getId().'/edit');

        // The user who own the task (admin)
        $initialUser = $this->task->getUser();

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Test PHPUnit';
        $form['task[content]'] = 'Contenu de test';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertEquals(Response::HTTP_OK, $statusCode);
        $this->assertContains('Superbe ! La tâche a bien été modifiée.', $crawler->filter('div.alert.alert-success')->text());

        // The user who own the task after modification
        $user = $this->task->getUser();

        // Assert that the user who own the task after the modification has not be modified
        $this->assertSame($initialUser, $user);
    }

    public function testTaskNotFound()
    {
        $this->logIn();
        $this->client->request('GET', '/tasks/999999/edit');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
        $crawler = $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('Oops ! La tâche n\'existe pas.', $crawler->filter('div.alert.alert-danger')->text());
    }
}
