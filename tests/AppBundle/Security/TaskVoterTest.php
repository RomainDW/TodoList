<?php


namespace Tests\AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Security\TaskVoter;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tests\AppBundle\MyTestCase;

class TaskVoterTest extends MyTestCase
{
    public function testSupportsSuccess()
    {
        $taskVoter = new TaskVoter();

        $user = new User(
            'Test',
            'password',
            'test@email.com'
        );
        $task = new Task(
            'test',
            'test',
            $user
        );

        $this->assertTrue($taskVoter->supports('delete', $task));
    }

    public function testSupportsWrongAttribute()
    {
        $taskVoter = new TaskVoter();

        $user = new User(
            'Test',
            'password',
            'test@email.com'
        );
        $task = new Task(
            'test',
            'test',
            $user
        );

        $this->assertFalse($taskVoter->supports('test', $task));
    }

    public function testSupportsWrongSubject()
    {
        $taskVoter = new TaskVoter();

        $user = new User(
            'Test',
            'password',
            'test@email.com'
        );

        $this->assertFalse($taskVoter->supports('delete', $user));
    }

    public function testVoteOnAttribute()
    {
        $taskVoter = new TaskVoter();
        $user = new User(
            'Test',
            'password',
            'test@email.com'
        );
        $task = new Task(
            'test',
            'test',
            $user
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $this->assertTrue($taskVoter->voteOnAttribute('delete', $task, $token));
    }

    public function testVoteOnAttributeUserNotLogged()
    {
        $taskVoter = new TaskVoter();
        $user = new User(
            'Test',
            'password',
            'test@email.com'
        );
        $task = new Task(
            'test',
            'test',
            $user
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(null);

        $this->assertFalse($taskVoter->voteOnAttribute('delete', $task, $token));
    }

    public function testVoteOnAttributeException()
    {
        $taskVoter = new TaskVoter();
        $user = new User(
            'Test',
            'password',
            'test@email.com'
        );
        $task = new Task(
            'test',
            'test',
            $user
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $this->expectException(LogicException::class);

        $this->assertFalse($taskVoter->voteOnAttribute('test', $task, $token));
    }

    public function testDeleteAdmin()
    {
        $taskVoter = new TaskVoter();
        $anonymous = new User(
            'Anonymous',
            'Anonymous',
            'anonymous@anonymous.com'
        );
        $admin = new User(
            'test',
            'password',
            'test@email.com',
            ['ROLE_ADMIN']
        );

        $task = new Task(
            'test',
            'test',
            $anonymous
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($admin);

        $this->assertTrue($taskVoter->voteOnAttribute('delete', $task, $token));
    }
}
