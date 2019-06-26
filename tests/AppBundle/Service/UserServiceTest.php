<?php


namespace Tests\AppBundle\Service;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
{
    /**
     * @var UserService
     */
    private $systemUnderTest;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $doctrine = $kernel->getContainer()->get('doctrine');
        $this->systemUnderTest = new UserService($doctrine);
    }

    public function testInitUser()
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'test';
        $userDTO->password = 'test';
        $userDTO->email = 'admin@email.com.com';

        $user = $this->systemUnderTest->initUser($userDTO);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test', $user->getUsername());
        $this->assertNotEquals('test', $user->getPassword());
        $this->assertEquals('admin@email.com.com', $user->getEmail());
    }

    public function testUpdateUser()
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'testUpdate';
        $userDTO->password = 'testUpdate';
        $userDTO->email = 'testUpdate@email.com';
        $userDTO->roles = ['ROLE_ADMIN'];

        $user = new User(
            'test',
            'test',
            'admin@email.com.com'
        );

        $this->systemUnderTest->updateUser($userDTO, $user);

        $this->assertEquals('testUpdate', $user->getUsername());
        $this->assertNotEquals('testUpdate', $user->getPassword());
        $this->assertEquals('testUpdate@email.com', $user->getEmail());
        $this->assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testFind()
    {
        $id = 1;
        $user = $this->systemUnderTest->find($id);
        $this->assertInstanceOf(User::class, $user);

        $id = 999;
        $this->expectException(UserNotFoundException::class);
        $this->systemUnderTest->find($id);
    }
}
