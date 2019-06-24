<?php


namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testImplementation()
    {
        $username = 'test';
        $password = 'test';
        $email = 'test@email.com';

        $user = new User($username, $password, $email);

        $this->assertSame($username, $user->getUsername());
        $this->assertNotSame($password, $user->getPassword());// password is hashed
        $this->assertSame($email, $user->getEmail());
    }
}
