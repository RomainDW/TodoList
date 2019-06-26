<?php


namespace Tests\AppBundle\DTO;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserDTOTest extends TestCase
{
    public function testCreateFromTask()
    {
        $userDTO = new UserDTO();
        $user = new User(
            'test',
            'test',
            'admin@email.com.com'
        );
        $userDTO->createFromUser($user);

        $this->assertEquals('test', $userDTO->username);
        $this->assertEquals('admin@email.com.com', $userDTO->email);
    }
}
