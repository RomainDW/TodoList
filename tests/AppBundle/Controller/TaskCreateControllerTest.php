<?php


namespace Tests\AppBundle\Controller;

use Tests\AppBundle\MyTestCase;

class TaskCreateControllerTest extends MyTestCase
{
    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/tasks/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "IS_AUTHENTICATED_ANONYMOUSLY"
        $this->login(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $this->client->request('GET', '/tasks/create');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testFormRendering()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/create');

        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testTaskCreation()
    {
        $this->login();
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Test';
        $form['task[content]'] = 'Contenu de test';
        $this->task = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $this->assertContains("Superbe ! La tâche a bien été ajoutée", $crawler->filter('div.alert.alert-success')->text());
    }
}
