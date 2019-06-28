<?php


namespace Tests\AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Tests\AppBundle\MyTestCase;

class LoginFormAuthenticatorTest extends MyTestCase
{
    /**
     * @var LoginFormAuthenticator
     */
    private $loginFormAuthenticator;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $urlGenerator = $this->client->getContainer()->get('router');
        $this->csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $passwordEncoder = $this->client->getContainer()->get('security.password_encoder');

        $this->loginFormAuthenticator = new LoginFormAuthenticator(
            $this->entityManager,
            $urlGenerator,
            $this->csrfTokenManager,
            $passwordEncoder
        );

        $this->request = new Request();
        $this->request->request->set('email', 'user@email.com');
        $this->request->request->set('password', 'password');
        $this->request->request->set('_csrf_token', 'token');
        $this->request->request->set('csrf_token', $this->request->request->get('_csrf_token'));
        $this->request->setSession($this->client->getContainer()->get('session'));
    }

    public function testGetCredentials()
    {
        $credentials = $this->loginFormAuthenticator->getCredentials($this->request);

        $this->assertEquals('user@email.com', $credentials['email']);
        $this->assertEquals('password', $credentials['password']);
        $this->assertEquals('token', $credentials['csrf_token']);
    }

    public function testGetUserFail()
    {
        $credentials = $this->loginFormAuthenticator->getCredentials($this->request);
        $userProvider = $this->createMock(UserProviderInterface::class);

        $this->expectException(InvalidCsrfTokenException::class);
        $this->loginFormAuthenticator->getUser($credentials, $userProvider);
    }

    public function testGetUserSuccess()
    {
        $credentials = $this->loginFormAuthenticator->getCredentials($this->request);
        $userProvider = $this->createMock(UserProviderInterface::class);
        $this->csrfTokenManager->method('isTokenValid')->willReturn(true);

        $this->assertInstanceOf(UserInterface::class, $this->loginFormAuthenticator->getUser($credentials, $userProvider));
    }

    public function testGetUserNotFound()
    {
        $this->request->request->set('email', 'test@email.com');

        $credentials = $this->loginFormAuthenticator->getCredentials($this->request);
        $userProvider = $this->createMock(UserProviderInterface::class);
        $this->csrfTokenManager->method('isTokenValid')->willReturn(true);

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $this->loginFormAuthenticator->getUser($credentials, $userProvider);
    }

    public function testCheckCredentialsSuccess()
    {
        $credentials = $this->loginFormAuthenticator->getCredentials($this->request);
        $user = new User(
            'test',
            'password',
            'test@email.com'
        );
        $this->assertTrue($this->loginFormAuthenticator->checkCredentials($credentials, $user));
    }

    public function testCheckCredentialsFail()
    {
        $credentials = $this->loginFormAuthenticator->getCredentials($this->request);
        $user = new User(
            'test',
            '123',
            'test@email.com'
        );
        $this->assertFalse($this->loginFormAuthenticator->checkCredentials($credentials, $user));
    }

    public function testOnAuthenticationSuccessWithTargetPath()
    {
        $providerKey = 'main';
        $this->request->getSession()->set('_security.'.$providerKey.'.target_path', '/tasks');
        $token = $this->createMock(TokenInterface::class);
        $response = $this->loginFormAuthenticator->onAuthenticationSuccess($this->request, $token, $providerKey);

        $this->assertTrue($response->isRedirect('/tasks'));
    }

    public function testOnAuthenticationSuccessWithoutTargetPath()
    {
        $providerKey = 'main';
//        $this->request->getSession()->set('_security.'.$providerKey.'.target_path', '/tasks');
        $token = $this->createMock(TokenInterface::class);
        $response = $this->loginFormAuthenticator->onAuthenticationSuccess($this->request, $token, $providerKey);

        $this->assertTrue($response->isRedirect('/'));
    }
}
