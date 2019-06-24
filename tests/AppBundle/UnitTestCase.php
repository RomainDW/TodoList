<?php


namespace Tests\AppBundle;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class UnitTestCase extends KernelTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        static::bootKernel();
        $this->client = static::$kernel->getContainer()->get('test.client');
        $this->client->setServerParameters([]);
    }

    protected function logIn($roles = ['ROLE_ADMIN', 'ROLE_USER'])
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';

        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        /** @var User $user */
        $user = $entityManager->getRepository('AppBundle:User')->findOneBy(['email' => 'test@email.com']);

        $token = new PostAuthenticationGuardToken($user, $firewall, $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
