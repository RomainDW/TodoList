<?php


namespace AppBundle\Service;


use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
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

    public function initTask(TaskDTO $taskDTO)
    {
        $task = new Task(
            $taskDTO->title,
            $taskDTO->content
        );

        return $task;
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
}