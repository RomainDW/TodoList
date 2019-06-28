<?php


namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\MyTestCase;

class TaskDeleteControllerTest extends MyTestCase
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

    public function testNotOwnedTaskDelete()
    {
        $userOwnsTheTask = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'user@email.com']);
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $userOwnsTheTask]);

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@email.com']);

        $this->logIn(["ROLE_USER", "ROLE_ADMIN"], $user);
        $this->client->request('POST', '/tasks/'.$task->getId().'/delete');
        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $statusCode);
    }

    public function testOwnedTaskDelete()
    {
        /** @var User $userOwnsTheTask */
        $userOwnsTheTask = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'user@email.com']);
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $userOwnsTheTask]);

        $this->logIn(["ROLE_USER"], $userOwnsTheTask);
        $this->client->request('POST', '/tasks/'.$task->getId().'/delete');
        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertEquals(Response::HTTP_FOUND, $statusCode);

        $crawler = $this->client->followRedirect();
        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertEquals(Response::HTTP_OK, $statusCode);
        $this->assertContains('Superbe ! La tâche a bien été supprimée.', $crawler->filter('div.alert.alert-success')->text());
    }
}
