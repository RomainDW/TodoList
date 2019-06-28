<?php


namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;
use Tests\AppBundle\MyTestCase;

class UserTest extends MyTestCase
{
    public function testImplementation()
    {
        $username = 'test';
        $password = 'test';
        $email = 'test@email.com';

        $user = new User($username, $password, $email);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertSame($username, $user->getUsername());
        $this->assertNotSame($password, $user->getPassword());// password is hashed
        $this->assertSame($email, $user->getEmail());
        $this->assertNotEmpty($user->getId());
        $this->assertNull($user->getSalt());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testUpdate()
    {
        $username = 'test';
        $password = 'test';
        $email = 'test@email.com';
        $user = new User($username, $password, $email);

        $usernameUpdate = 'testUpdate';
        $passwordUpdate = 'testUpdate';
        $emailUpdate = 'testUpdate@email.com';
        $user->update(
            $usernameUpdate,
            $passwordUpdate,
            $emailUpdate
        );

        $this->assertSame($usernameUpdate, $user->getUsername());
        $this->assertNotSame($passwordUpdate, $user->getPassword());// password is hashed
        $this->assertSame($emailUpdate, $user->getEmail());
    }

    public function testEraseCredentials()
    {
        $username = 'test';
        $password = 'test';
        $email = 'test@email.com';
        $user = new User($username, $password, $email);

        $user->eraseCredentials();
        $this->assertNotSame('test', $user->getPassword());
    }
}
