<?php


namespace Tests\AppBundle\Service;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Exception\TaskNotFoundException;
use AppBundle\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\MyTestCase;

class TaskServiceTest extends MyTestCase
{
    /**
     * @var TaskService
     */
    private $systemUnderTest;

    public function setUp()
    {
        parent::setUp();
        $doctrine = $this->client->getContainer()->get('doctrine');
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
            'admin@email.com.com'
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
        $id = $this->task->getId();
        $task = $this->systemUnderTest->find($id);
        $this->assertInstanceOf(Task::class, $task);

        $id = 99999999999999999999;
        $this->expectException(TaskNotFoundException::class);
        $this->systemUnderTest->find($id);
    }


    public function testSave()
    {
        $user = new User(
            'test',
            'test',
            'testSave@email.com'
        );
        $taskToFind = new Task('test save', 'test save', $user);
        $this->entityManager->persist($user);
        $this->systemUnderTest->save($taskToFind);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => 'test save']);

        $this->assertSame($taskToFind, $task);
    }

    public function testToggle()
    {
        $task = $this->task;

        $task->toggle(false);
        $this->assertFalse($task->isDone());

        $this->systemUnderTest->toggle($task);
        $this->assertTrue($task->isDone());
    }

    public function testRemove()
    {
        $this->systemUnderTest->remove($this->task);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => 'Tâche n°1']);

        $this->assertNull($task);
    }
}
