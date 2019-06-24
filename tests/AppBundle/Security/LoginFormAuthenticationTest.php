<?php


namespace Tests\AppBundle\Security;

use AppBundle\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LoginFormAuthenticationTest extends KernelTestCase
{
    public function testGetLoginUrl()
    {
        $entityManager = $this->createMock(EntityManager::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $tokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $loginFormAuthenticator = new LoginFormAuthenticator(
            $entityManager,
            $urlGenerator,
            $tokenManager,
            $passwordEncoder
        );

        $request = Request::create('/login', 'POST');
        $request->attributes->set('_route', 'login');
        $this->assertTrue($loginFormAuthenticator->supports($request));

        $request = Request::create('/login', 'GET');
        $request->attributes->set('_route', 'login');
        $this->assertFalse($loginFormAuthenticator->supports($request));

        $request = Request::create('/login', 'POST');
        $this->assertFalse($loginFormAuthenticator->supports($request));
    }
}
