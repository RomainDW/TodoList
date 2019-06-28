<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\FormHandler\CreateUserFormHandler;
use AppBundle\Service\UserService;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\AppBundle\MyTestCase;

class CreateUserFormHandlerTest extends MyTestCase
{
    /**
     * @var UserService
     */
    private $userService;

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

        $this->userService = $this->createMock(UserService::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(UserService::class, $this->userService);
        $this->assertInstanceOf(ValidatorInterface::class, $this->validator);
        $this->assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $createTaskFormHandler = new CreateUserFormHandler($this->userService, $this->validator, $this->flashBag);
        $this->assertInstanceOf(
            CreateUserFormHandler::class,
            $createTaskFormHandler
        );
    }
}
