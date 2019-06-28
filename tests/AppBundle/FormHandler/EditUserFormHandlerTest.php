<?php


namespace Tests\AppBundle\FormHandler;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\FormHandler\CreateUserFormHandler;
use AppBundle\FormHandler\EditUserFormHandler;
use AppBundle\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\AppBundle\MyTestCase;

class EditUserFormHandlerTest extends MyTestCase
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
     * @var EditUserFormHandler
     */
    private $editUserFormHandler;

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

        $this->editUserFormHandler = new EditUserFormHandler($this->userService, $this->validator, $this->flashBag);
    }

    public function testDependencies()
    {
        $this->assertInstanceOf(UserService::class, $this->userService);
        $this->assertInstanceOf(ValidatorInterface::class, $this->validator);
        $this->assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $editUserFormHandler = new EditUserFormHandler($this->userService, $this->validator, $this->flashBag);
        $this->assertInstanceOf(
            EditUserFormHandler::class,
            $editUserFormHandler
        );
    }

    public function testHandleSuccess()
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'testUpdate';
        $userDTO->password = 'testUpdate';
        $userDTO->email = 'test@email.com';
        $userDTO->roles = ['ROLE_USER'];

        $user = new User(
            'test',
            'test',
            'test@email.com',
            ['ROLE_USER']
        );

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($userDTO);

        $this->assertTrue($this->editUserFormHandler->handle($this->form, $user));
    }

    public function testHandleNotSubmitted()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com',
            ['ROLE_USER']
        );

        $this->form->method('isSubmitted')->willReturn(false);

        $this->assertFalse($this->editUserFormHandler->handle($this->form, $user));
    }

    public function testHandleNotValid()
    {
        $user = new User(
            'test',
            'test',
            'test@email.com',
            ['ROLE_USER']
        );

        $this->form->method('isValid')->willReturn(false);

        $this->assertFalse($this->editUserFormHandler->handle($this->form, $user));
    }

    public function testHandleTaskNotValid()
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'testUpdate';
        $userDTO->password = 'testUpdate';
        $userDTO->email = 'user@email.com'; //email already exist
        $userDTO->roles = ['ROLE_USER'];

        $user = new User(
            'test',
            'test',
            'test@email.com',
            ['ROLE_USER']
        );

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);
        $this->form->method('getData')->willReturn($userDTO);

        $this->assertFalse($this->editUserFormHandler->handle($this->form, $user));
    }
}
