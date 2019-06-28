<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\FormHandler\CreateTaskFormHandler;
use AppBundle\Service\TaskService;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\AppBundle\MyTestCase;

class CreateTaskFormHandlerTest extends MyTestCase
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
     * @var FlashBagInterface
     */
    private $flashBag;

    public function setUp()
    {
        parent::setUp();

        $this->taskService = $this->createMock(TaskService::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
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
}
