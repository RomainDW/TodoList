<?php


namespace Tests\AppBundle\DTO;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskDTOTest extends TestCase
{
    public function testCreateFromTask()
    {
        $taskDTO = new TaskDTO();
        $user = new User(
            'test',
            'test',
            'admin@email.com.test'
        );
        $task = new Task(
            'test',
            'test',
            $user
        );
        $taskDTO->createFromTask($task);

        $this->assertEquals('test', $taskDTO->title);
        $this->assertEquals('test', $taskDTO->content);
    }
}
