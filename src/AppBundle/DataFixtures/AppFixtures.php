<?php


namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user1 = new User(
            'Admin',
            'password',
            'admin@email.com',
            ['ROLE_USER', 'ROLE_ADMIN']
        );

        $user2 = new User(
            'User',
            'password',
            'user@email.com',
            ['ROLE_USER']
        );

        $manager->persist($user1);
        $manager->persist($user2);

        for ($i = 1; $i < 11; $i++) {
            $task = new Task(
                'Tâche n°'.$i,
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                labore et dolore magna aliqua. Faucibus nisl tincidunt eget nullam non. Ullamcorper eget nulla facilisi 
                etiam dignissim diam quis. Mauris sit amet massa vitae tortor. Nulla facilisi cras fermentum odio eu 
                feugiat pretium nibh ipsum. Congue mauris rhoncus aenean vel elit. Dui sapien eget mi proin sed libero 
                enim sed faucibus. Sit amet risus nullam eget felis. Semper viverra nam libero justo laoreet sit amet 
                cursus sit. Cras adipiscing enim eu turpis egestas pretium aenean. Auctor elit sed vulputate mi. Blandit 
                massa enim nec dui nunc mattis enim ut. Volutpat est velit egestas dui id ornare arcu. Massa eget egestas 
                purus viverra accumsan in. Habitant morbi tristique senectus et netus et malesuada fames. Id faucibus 
                nisl tincidunt eget nullam non nisi. Aliquam ut porttitor leo a diam.',
                $user1,
                ($i < 4) ? true : false
            );

            $manager->persist($task);
        }

        for ($i = 11; $i < 21; $i++) {
            $task = new Task(
                'Tâche n°'.$i,
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                labore et dolore magna aliqua. Faucibus nisl tincidunt eget nullam non. Ullamcorper eget nulla facilisi 
                etiam dignissim diam quis. Mauris sit amet massa vitae tortor. Nulla facilisi cras fermentum odio eu 
                feugiat pretium nibh ipsum. Congue mauris rhoncus aenean vel elit. Dui sapien eget mi proin sed libero 
                enim sed faucibus. Sit amet risus nullam eget felis. Semper viverra nam libero justo laoreet sit amet 
                cursus sit. Cras adipiscing enim eu turpis egestas pretium aenean. Auctor elit sed vulputate mi. Blandit 
                massa enim nec dui nunc mattis enim ut. Volutpat est velit egestas dui id ornare arcu. Massa eget egestas 
                purus viverra accumsan in. Habitant morbi tristique senectus et netus et malesuada fames. Id faucibus 
                nisl tincidunt eget nullam non nisi. Aliquam ut porttitor leo a diam.',
                $user2,
                rand(0, 1)
            );

            $manager->persist($task);
        }

        $manager->flush();
    }
}
