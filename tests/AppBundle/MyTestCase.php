<?php


namespace Tests\AppBundle;

use AppBundle\DataFixtures\AppFixtures;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class MyTestCase extends KernelTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Application
     */
    protected $application;

    public function setUp()
    {
        $kernel = $this->bootKernel();
        $this->application = new Application($kernel);
        $this->client = $kernel->getContainer()->get('test.client');
        $this->client->setServerParameters([]);
        $container = $this->client->getContainer();
        $doctrine = $container->get('doctrine');
        $this->entityManager = $doctrine->getManager();
        $this->loadFixtures();
        $this->task = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => 'Tâche n°1']);
    }

    protected function logIn($roles = ['ROLE_ADMIN', 'ROLE_USER'], User $user = null)
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';

        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        if ($user == null) {
            /** @var User $user */
            $user = $entityManager->getRepository('AppBundle:User')->findOneBy(['email' => 'admin@email.com']);
        }

        $token = new PostAuthenticationGuardToken($user, $firewall, $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function loadFixtures()
    {
        $loader = new Loader();
        $loader->addFixture(new AppFixtures());

        $purger = new ORMPurger($this->entityManager);
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}
