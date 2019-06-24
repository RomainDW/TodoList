<?php


namespace Tests\AppBundle\Exception;

use AppBundle\Exception\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use Throwable;

class UserNotFoundExceptionTest extends TestCase
{
    public function testInstance()
    {
        $exception = new UserNotFoundException();

        $this->assertInstanceOf(Throwable::class, $exception);
    }
}
