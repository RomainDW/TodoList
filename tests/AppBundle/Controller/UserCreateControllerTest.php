<?php


namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\MyTestCase;

class UserCreateControllerTest extends MyTestCase
{
    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/users/create');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/users/create');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_ADMIN"
        $this->login(['ROLE_ADMIN']);
        $this->client->request('GET', '/users/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormRendering()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/users/create');

        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testUserCreation()
    {
        $this->login();
        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'PHPUnit User';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'test@email.com';
        $form['user[roles]'] = ['ROLE_USER'];

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();
        $this->assertEquals(Response::HTTP_OK, $statusCode);
        $this->assertContains("Superbe ! L'utilisateur a bien été ajouté.", $crawler->filter('div.alert.alert-success')->text());
    }
}
