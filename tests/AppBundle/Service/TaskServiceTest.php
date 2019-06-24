<?php


namespace Tests\AppBundle\Service;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Exception\TaskNotFoundException;
use AppBundle\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskServiceTest extends KernelTestCase
{
    /**
     * @var TaskService
     */
    private $systemUnderTest;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $doctrine = $kernel->getContainer()->get('doctrine');
        $this->systemUnderTest = new TaskService($doctrine);
    }

    public function testInitTask()
    {
        $taskDTO = new TaskDTO();
        $taskDTO->title = 'test';
        $taskDTO->content = 'test';
        $taskDTO->content = 'test';

        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $task = $this->systemUnderTest->initTask($taskDTO, $user);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('test', $task->getTitle());
        $this->assertEquals('test', $task->getContent());
        $this->assertSame($user, $task->getUser());
    }

    public function testUpdateTask()
    {
        $taskDTO = new TaskDTO();
        $taskDTO->title = 'testUpdate';
        $taskDTO->content = 'testUpdate';

        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $task = new Task('test', 'test', $user);

        $this->systemUnderTest->updateTask($taskDTO, $task);

        $this->assertEquals('testUpdate', $task->getTitle());
        $this->assertEquals('testUpdate', $task->getContent());
        $this->assertSame($user, $task->getUser());
    }

    public function testFind()
    {
        $id = 1;
        $task = $this->systemUnderTest->find($id);
        $this->assertInstanceOf(Task::class, $task);

        $id = 999;
        $this->expectException(TaskNotFoundException::class);
        $this->systemUnderTest->find($id);
    }
}
