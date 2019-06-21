<?php


namespace AppBundle\DTO;


use AppBundle\Entity\User;

class UserDTO
{
    public $username;
    public $password;
    public $email;
    public $roles;

    public function createFromUser(User $user)
    {
        $this->username = $user->getUsername();
        $this->password = $user->getPassword();
        $this->email = $user->getEmail();
        $this->roles = $user->getRoles();
    }
}