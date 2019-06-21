<?php


namespace AppBundle\Service;


use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\Exception\UserNotFoundException;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * TaskService constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param UserDTO $userDTO
     * @return User
     */
    public function initUser(UserDTO $userDTO)
    {

//        var_dump($userDTO->roles);die();
        $user = new User(
            $userDTO->username,
            $userDTO->password,
            $userDTO->email,
            $userDTO->roles
        );

        return $user;
    }

    public function save(User $user)
    {
        $manager = $this->doctrine->getManager();
        $manager->persist($user);
        $manager->flush();
    }

    public function updateUser(UserDTO $userDTO, User $user)
    {
        $user->update(
            $userDTO->username,
            $userDTO->password,
            $userDTO->email,
            $userDTO->roles
        );
    }

    /**
     * @param $id
     * @return object|null
     * @throws UserNotFoundException
     */
    public function find($id)
    {
        $manager = $this->doctrine->getManager();
        $user = $manager->getRepository(User::class)->find($id);

        if (is_null($user)) {
            throw new UserNotFoundException("L'utilisateur n'existe pas.");
        }

        return $user;
    }
}