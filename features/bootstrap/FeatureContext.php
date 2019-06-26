<?php

use AppBundle\Entity\User;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @Given I am logged in as admin
     */
    public function iAmAuthenticatedAsAdmin()
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];

        $this->login($roles);
    }

    /**
     * @Given I am logged in as simple user
     */
    public function iAmAuthenticatedAsUser()
    {
        $roles = ['ROLE_USER'];

        $this->login($roles);
    }

    /**
     * @Given /^(?:|I )am logged in with email "(?P<email>(?:[^"]|\\")*)"$/
     * @param $email
     */
    public function iAmAuthenticatedWithCredentials($email)
    {
        $this->login([], $email);
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    private function login(array $roles = null, $email = null)
    {
        $session = $this->kernel->getContainer()->get('session');

        $firewall = 'main';

        $entityManager = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');

        if ($email !== null) {
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            $roles = $user->getRoles();
        } else {
            /** @var User $user */
            $user = $entityManager->getRepository('AppBundle:User')->findOneBy(['email' => 'admin@email.com']);
        }

        $token = new PostAuthenticationGuardToken($user, $firewall, $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $this->getSession()->setCookie($session->getName(), $session->getId());
    }
}
