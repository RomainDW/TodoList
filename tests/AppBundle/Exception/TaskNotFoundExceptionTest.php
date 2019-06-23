<?php


namespace Tests\AppBundle\Exception;

use AppBundle\Exception\TaskNotFoundException;
use PHPUnit\Framework\TestCase;
use Throwable;

class TaskNotFoundExceptionTest extends TestCase
{
    public function testInstance()
    {
        $exception = new TaskNotFoundException();

        $this->assertInstanceOf(Throwable::class, $exception);
    }
}
