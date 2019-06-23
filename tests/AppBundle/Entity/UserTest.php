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

        static::assertSame($username, $user->getUsername());
        static::assertNotSame($password, $user->getPassword());// password is hashed
        static::assertSame($email, $user->getEmail());
    }
}
