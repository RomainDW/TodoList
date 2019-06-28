<?php


namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;
use Tests\AppBundle\MyTestCase;

class TaskTest extends MyTestCase
{
    public function testImplementation()
    {
        $title = 'test';
        $content = 'test';
        $user = new User(
            'test',
            'test',
            'admin@email.com.com'
        );

        $task = new Task($title, $content, $user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->assertSame($title, $task->getTitle());
        $this->assertSame($content, $task->getContent());
        $this->assertSame($user, $task->getUser());
        $this->assertNotEmpty($task->getId());
    }

    public function testToggle()
    {
        $title = 'test';
        $content = 'test';
        $user = new User(
            'test',
            'test',
            'admin@email.com.com'
        );

        $task = new Task($title, $content, $user);

        $task->toggle(false);
        $this->assertFalse($task->isDone());

        $task->toggle(true);
        $this->assertTrue($task->isDone());
    }

    public function testUpdate()
    {
        $title = 'test';
        $content = 'test';
        $user = new User(
            'test',
            'test',
            'admin@email.com.com'
        );
        $task = new Task($title, $content, $user);

        $titleUpdate = 'testUpdate';
        $contentUpdate = 'testUpdate';
        $task->update(
            $titleUpdate,
            $contentUpdate
        );

        $this->assertEquals($titleUpdate, $task->getTitle());
        $this->assertEquals($contentUpdate, $task->getContent());
    }
}
