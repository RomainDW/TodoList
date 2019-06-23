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

        if (is_null($task)) {
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
}
