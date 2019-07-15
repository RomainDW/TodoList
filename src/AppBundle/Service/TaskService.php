<?php


namespace AppBundle\Service;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Exception\TaskNotFoundException;
use Doctrine\Common\Persistence\ManagerRegistry;

class TaskService
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

    public function initTask(TaskDTO $taskDTO, User $user)
    {
        $task = new Task(
            $taskDTO->title,
            $taskDTO->content,
            $user
        );

        return $task;
    }

    public function updateTask(TaskDTO $taskDTO, Task $task)
    {
        $task->update(
            $taskDTO->title,
            $taskDTO->content
        );
    }

    public function save(Task $task)
    {
        $manager = $this->doctrine->getManager();
        $manager->persist($task);
        $manager->flush();
    }

    public function remove(Task $task)
    {
        $manager = $this->doctrine->getManager();
        $manager->remove($task);
        $manager->flush();
    }

    /**
     * @param $id
     * @return object|null
     * @throws TaskNotFoundException
     */
    public function find($id)
    {
        $manager = $this->doctrine->getManager();
        $task = $manager->getRepository(Task::class)->find($id);

        if (null === $task) {
            throw new TaskNotFoundException("La tâche n'existe pas.");
        }

        return $task;
    }

    public function toggle(Task $task)
    {
        $task->toggle(!$task->isDone());
        $manager = $this->doctrine->getManager();
        $manager->flush();
    }

    public function linkToAnonymous()
    {
        $manager = $this->doctrine->getManager();
        $tasks = $manager->getRepository(Task::class)->findBy(['user' => null]);
        if ($tasks == null) {
            return 'There are no tasks belonging to anyone';
        }
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'anonymous@anonymous.com']);

        if ($user == null) {
            $user = new User(
                'Anonymous',
                'anonymous',
                'anonymous@anonymous.com',
                ['IS_AUTHENTICATED_ANONYMOUSLY']
            );
            $manager->persist($user);
            $manager->flush();
        }

        /** @var Task $task */
        foreach ($tasks as $task) {
            $task->setAnonymousUser($user);
            $manager->persist($task);
        }

        $manager->flush();

        return 'Done.';
    }
}
