<?php


namespace Tests\AppBundle\DTO;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Tests\AppBundle\UnitTestCase;

class TaskDTOTest extends UnitTestCase
{
    public function testCreateFromTask()
    {
        $taskDTO = new TaskDTO();
        $user = new User(
            'test',
            'test',
            'test@email.test'
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
