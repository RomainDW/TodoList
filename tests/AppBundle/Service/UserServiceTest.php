<?php


namespace Tests\AppBundle\Service;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Service\UserService;
use Tests\AppBundle\MyTestCase;

class UserServiceTest extends MyTestCase
{
    /**
     * @var UserService
     */
    private $systemUnderTest;

    public function setUp()
    {
        parent::setUp();
        $doctrine = $this->client->getContainer()->get('doctrine');
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
        $id = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@email.com'])->getId();
        $user = $this->systemUnderTest->find($id);
        $this->assertInstanceOf(User::class, $user);

        $id = 99999999999999;
        $this->expectException(UserNotFoundException::class);
        $this->systemUnderTest->find($id);
    }

    public function testSave()
    {
        $userToFind = new User(
            'test',
            'test',
            'testSave@email.com'
        );
        $this->systemUnderTest->save($userToFind);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'testSave@email.com']);

        $this->assertSame($userToFind, $user);
    }
}
