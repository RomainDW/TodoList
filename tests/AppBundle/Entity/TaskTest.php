<?php


namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testImplementation()
    {
        $title = 'test';
        $content = 'test';
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $task = new Task($title, $content, $user);

        $this->assertSame($title, $task->getTitle());
        $this->assertSame($content, $task->getContent());
        $this->assertSame($user, $task->getUser());
    }
}
