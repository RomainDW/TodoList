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

        static::assertSame($title, $task->getTitle());
        static::assertSame($content, $task->getContent());
        static::assertSame($user, $task->getUser());
    }
}
