<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\FormHandler\CreateTaskFormHandler;
use AppBundle\FormHandler\EditTaskFormHandler;
use AppBundle\Service\TaskService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\AppBundle\MyTestCase;

class EditTaskFormHandlerTest extends MyTestCase
{
    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var MockObject
     */
    private $flashBag;

    /**
     * @var EditTaskFormHandler
     */
    private $editTaskFormHandler;

    /**
     * @var MockObject
     */
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->taskService = new TaskService($this->client->getContainer()->get('doctrine'));
        $this->validator = $this->client->getContainer()->get('validator');
        $this->flashBag = $this->createMock(FlashBagInterface::class);

        $this->form = $this->createMock(FormInterface::class);

        $this->editTaskFormHandler = new EditTaskFormHandler($this->taskService, $this->validator, $this->flashBag);
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(TaskService::class, $this->taskService);
        $this->assertInstanceOf(ValidatorInterface::class, $this->validator);
        $this->assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $createTaskFormHandler = new EditTaskFormHandler($this->taskService, $this->validator, $this->flashBag);
        $this->assertInstanceOf(
            EditTaskFormHandler::class,
            $createTaskFormHandler
        );
    }

    public function testHandleSuccess()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $taskDTO = new TaskDTO();
        $taskDTO->title = 'testUpdate';
        $taskDTO->content = 'testUpdate';

        $task = new Task(
            'test',
            'test',
            $user
        );

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($taskDTO);

        $this->assertTrue($this->editTaskFormHandler->handle($this->form, $task));
    }

    public function testHandleNotSubmitted()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $task = new Task(
            'test',
            'test',
            $user
        );

        $this->form->method('isSubmitted')->willReturn(false);

        $this->assertFalse($this->editTaskFormHandler->handle($this->form, $task));
    }

    public function testHandleNotValid()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $task = new Task(
            'test',
            'test',
            $user
        );

        $this->form->method('isValid')->willReturn(false);

        $this->assertFalse($this->editTaskFormHandler->handle($this->form, $task));
    }

    public function testHandleTaskNotValid()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $taskDTO = new TaskDTO();
        $taskDTO->title = '';
        $taskDTO->content = 'testUpdate';

        $task = new Task(
            'test',
            'test',
            $user
        );

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($taskDTO);

        $this->assertFalse($this->editTaskFormHandler->handle($this->form, $task));
    }
}
