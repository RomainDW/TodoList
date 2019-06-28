<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\DTO\UserDTO;
use AppBundle\FormHandler\CreateUserFormHandler;
use AppBundle\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\FormInterface;
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

    /**
     * @var CreateUserFormHandler
     */
    private $createTaskFormHandler;

    /**
     * @var MockObject
     */
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->userService = new UserService($this->client->getContainer()->get('doctrine'));
        $this->validator = $this->client->getContainer()->get('validator');
        $this->flashBag = $this->createMock(FlashBagInterface::class);

        $this->form = $this->createMock(FormInterface::class);

        $this->createTaskFormHandler = new CreateUserFormHandler($this->userService, $this->validator, $this->flashBag);
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

    public function testHandleSuccess()
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'test';
        $userDTO->password = 'test';
        $userDTO->email = 'test@email.com';
        $userDTO->roles = ['ROLE_USER'];

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($userDTO);

        $this->assertTrue($this->createTaskFormHandler->handle($this->form));
    }

    public function testHandleNotSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $this->assertFalse($this->createTaskFormHandler->handle($this->form));
    }

    public function testHandleNotValid()
    {
        $this->form->method('isValid')->willReturn(false);

        $this->assertFalse($this->createTaskFormHandler->handle($this->form));
    }

    public function testHandleTaskNotValid()
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'test';
        $userDTO->password = 'test';
        $userDTO->email = 'user@email.com'; // email already exist
        $userDTO->roles = ['ROLE_USER'];

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($userDTO);

        $this->assertFalse($this->createTaskFormHandler->handle($this->form));
    }
}
