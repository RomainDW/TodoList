<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\User;
use AppBundle\FormHandler\CreateTaskFormHandler;
use AppBundle\Service\TaskService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\AppBundle\MyTestCase;

class CreateTaskFormHandlerTest extends MyTestCase
{
    /**
     * @var MockObject
     */
    private $taskService;

    /**
     * @var MockObject
     */
    private $validator;

    /**
     * @var MockObject
     */
    private $flashBag;

    /**
     * @var CreateTaskFormHandler
     */
    private $createTaskFormHandler;

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

        $this->createTaskFormHandler = new CreateTaskFormHandler($this->taskService, $this->validator, $this->flashBag);
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(TaskService::class, $this->taskService);
        $this->assertInstanceOf(ValidatorInterface::class, $this->validator);
        $this->assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $createTaskFormHandler = new CreateTaskFormHandler($this->taskService, $this->validator, $this->flashBag);
        $this->assertInstanceOf(
            CreateTaskFormHandler::class,
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
        $taskDTO->title = 'test';
        $taskDTO->content = 'test';

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($taskDTO);

        $this->assertTrue($this->createTaskFormHandler->handle($this->form, $user));
    }

    public function testHandleNotSubmitted()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $this->form->method('isSubmitted')->willReturn(false);

        $this->createTaskFormHandler->handle($this->form, $user);
        $this->assertFalse($this->createTaskFormHandler->handle($this->form, $user));
    }

    public function testHandleNotValid()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com'
        );

        $this->form->method('isValid')->willReturn(false);

        $this->assertFalse($this->createTaskFormHandler->handle($this->form, $user));
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
        $taskDTO->content = 'test';

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($taskDTO);

        $this->assertFalse($this->createTaskFormHandler->handle($this->form, $user));
    }
}
