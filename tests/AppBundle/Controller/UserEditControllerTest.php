<?php


namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\MyTestCase;

class UserEditControllerTest extends MyTestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'user@email.com']);
    }

    public function testSecure()
    {
        //User not logged
        $this->client->request('GET', '/users/'.$this->user->getId().'/edit');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        //User logged with role "ROLE_USER"
        $this->login(['ROLE_USER']);
        $this->client->request('GET', '/users/'.$this->user->getId().'/edit');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        //User logged with role "ROLE_ADMIN"
        $this->login(['ROLE_ADMIN']);
        $this->client->request('GET', '/users/'.$this->user->getId().'/edit');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormRendering()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/users/1/edit');

        $this->assertEquals(1, $crawler->filter('form')->count());
    }

    public function testUserEdit()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/users/'.$this->user->getId().'/edit');

        $form = $crawler->selectButton('Modifier')->form();
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
        $this->assertContains("Superbe ! L'utilisateur a bien été modifié", $crawler->filter('div.alert.alert-success')->text());
    }
}
